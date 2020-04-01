<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'token', 'site', 'phone', 'email', 'addres', 'direct'
    ];

    public function incomes()
    {
        return $this->hasMany(Income::class, 'company_id', 'id');
    }

    public function registerActs()
    {
        return $this->hasMany(RegisterAct::class, 'company_id', 'id');
    }

    public function incomePlans()
    {
        return $this->hasMany(IncomePlan::class, 'company', 'id');
    }

    public function regularClients()
    {
        return $this->hasMany(Regular::class, 'company_id', 'id');
    }

    public function activeRegularClients()
    {
        return $this->regularClients->filter(function ($regularClients) {
            return $regularClients->disabled;
        });
    }

    public function NoActiveRegularClients()
    {
        return $this->regularClients->filter(function ($regularClients) {
            return !$regularClients->disabled;
        });
    }

    public function act()
    {
        return $this->hasMany(Act::class, 'company_id', 'id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'company_id', 'id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'company_id', 'id');
    }

    public function clientChekingAccounts()
    {
        return $this->hasMany(ClientCheckingAccount::class, 'company_id', 'id');
    }

    public function activeClients()
    {
        return $this->clients->filter(function ($client) {
            return !$client->disabled;
        });
    }

    public function noActiveClients()
    {
        return $this->clients->filter(function ($client) {
            return $client->disabled;
        });
    }

    public function activeServices()
    {
        return $this->services->filter(function ($service) {
            return !$service->disabled;
        });
    }

    public function noActiveAct()
    {
        return $this->incomes->filter(function ($incomes) {
            return !$incomes->ready_acts;
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'company_id', 'id')->orderBy('name');
    }

    public function payServices()
    {
        return $this->hasMany(PayService::class, 'company_id', 'id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id', 'id');
    }

    public function activeIncomeServices()
    {
        return $this->services->filter(function ($services) {
            return $services->income;
        });
    }

    public function getIdActiveServices()
    {
        if (!empty($this->activeIncomeServices())) {
            foreach ($this->activeIncomeServices() as $activeServices) {
                $active[] = $activeServices->id;
            }
            if (!empty($active)) {
                return $active;
            }
            return null;
        }
    }

    public function inIncomesServices($dateFrom, $dateTo)
    {
        $active = $this->getIdActiveServices();
        return $this->incomes->filter(function ($incomes) use ($active, $dateFrom, $dateTo) {
            if (in_array($incomes->service, $active) && $incomes->income_date >= $dateFrom && $incomes->income_date <= $dateTo) {
                return true;
            }
            return false;
        });
    }

    public function activePayServices()
    {
        return $this->payServices->filter(function ($pay_services) {
            return $pay_services->income;
        });
    }

    public function getIdActivePayServices()
    {
        if (!empty($this->activePayServices())) {
            foreach ($this->activePayServices() as $activePayServices) {
                $active[] = $activePayServices->id;
            }
            return $active;
        }
    }

    public function inIncomes($dateFrom, $dateTo)
    {
        $active = $this->getIdActivePayServices();
            return $this->incomes->filter(function ($incomes) use ($active, $dateFrom, $dateTo) {
                if (in_array($incomes->pay_service, $active) && $incomes->income_date >= $dateFrom && $incomes->income_date <= $dateTo) {
                    return true;
                }
             return false;
            });
    }

    public function getActivePayServices()
    {
        foreach ($this->activePayServices() as $activePayServices) {
            return $activePayServices;
        }
    }


    public function accountNumber()
    {
        return $this->hasMany(AccountNumber::class, 'company_id', 'id');
    }

    public function generateAccountNumber($accountNumber, $count)
    {
        return str_pad($accountNumber, $count, "0", STR_PAD_RIGHT);
    }
}
