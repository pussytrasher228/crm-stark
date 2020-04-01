<?php

namespace App\Console\Commands;

use App\Entity\Company;
use App\Entity\Income;
use App\Services\Api\DataService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetApiDataCommand extends Command
{
    const DEALS_COUNT = 500;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update incomes';
    /**
     * @var DataService
     */
    private $dataService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DataService $dataService)
    {
        parent::__construct();
        $this->dataService = $dataService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company = Company::where(['id' => 1])->first();

        if (!$company) {
            throw new NotFoundHttpException('Такой компании не найдено');
        }

        $this->dataService->authorize();

        $pipelineId = 0;
        $pipeline = null;
        $statuses = [];

        $incomeTimestamp = Income::where('company_id', $company->id)->max('income_date');

        if ($incomeTimestamp) {
            $carbon = new Carbon($incomeTimestamp);
            $leads = $this->getDeals($carbon->timestamp);
        } else {
            $leads = $this->getDeals();
        }



        $externalIds = Income::where('company_id', $company->id)->get()->pluck('external_id')->toArray();

        $i = 0;

        $users = $this->dataService->getApiUsers('/api/v2/account', [
            'with' => 'users'
        ]);

        foreach ($leads as $lead) {
            if ($pipelineId != $lead['pipeline']['id']) {
                $pipelineId = $lead['pipeline']['id'];
                $pipeline = $this->dataService->getApiItems('/api/v2/pipelines?id=' . $pipelineId);
                $pipeline = array_first($pipeline);
                $statuses = $pipeline['statuses'];
            }
            if (isset($statuses[$lead['status_id']]) && $statuses[$lead['status_id']]['name'] == 'Оплачено') {
                if (!empty($lead['company'])) {
                    $leadCompany = $this->dataService->getApiItems('/api/v2/companies', [
                        'id' => $lead['company']['id'],
                    ])[0];
                }
                $payService = null;
                $service = null;

                if (!empty($lead['custom_fields'])) {
                    foreach ($lead['custom_fields'] as $custom_field) {
                        if (isset($custom_field['name']) && $custom_field['name'] == 'Оплата') {
                            $payService = $custom_field['values'][0]['value'];
                        }

                        if (isset($custom_field['name']) && $custom_field['name'] == 'Услуги') {
                            $service = $custom_field['values'][0]['value'];
                        }
                    }
                }

                if (in_array($lead['id'], $externalIds)) {
                    $income = Income::where('external_id', $lead['id'])->first();

                    $income->update([
                        'category' => $pipeline['name'],
                        'sum' => $lead['sale'],
                        'user' => isset($users[$lead['created_by']]) ? $users[$lead['created_by']]['name'] : 'Без юзера',
                        'comment' => $lead['name'],
                        'income_date' => $lead['closed_at'],
                        'client' => isset($leadCompany) ? $leadCompany['name'] : 'Без компании',
                        'service' => $service,
                        'pay_service' => $payService,
                    ]);
                } else {
                    $income = Income::make([
                        'category' => $pipeline['name'],
                        'sum' => $lead['sale'],
                        'user' => isset($users[$lead['created_by']]) ? $users[$lead['created_by']]['name'] : 'Без юзера',
                        'comment' => $lead['name'],
                        'company_id' => $company->id,
                        'income_date' => $lead['closed_at'],
                        'client' => isset($leadCompany) ? $leadCompany['name'] : 'Без компании',
                        'service' => $service,
                        'pay_service' => $payService,
                        'external_id' => $lead['id'],
                    ]);

                    $income->saveOrFail();
                }
            }
        }
    }

    public function getDeals(int $timestampFrom = null): \Generator
    {
        $index = 1;

        while (true) {
            $leads = $this->dataService->getApiItems('/api/v2/leads', [
                'limit_rows' => self::DEALS_COUNT,
                'limit_offset' => self::DEALS_COUNT * ($index - 1),
                'filter' => [
                    'date_modify' => [
                        'from' => $timestampFrom,
                    ]
                ]
            ]);

            foreach ($leads as $lead) {
                yield $lead;
            }

            if (count($leads) < self::DEALS_COUNT) {
                break;
            }

            $index++;
        }
    }
}
