
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">

        {{--
            First element (left floated)
            This month and past month filters
        --}}
        <div>
            <form action="?token={{ $company->token }}" method="GET" class="d-inline">
                    <input type="hidden" name="date_from" id="date_from" value="{{date("Y-m-01")}}">
                    <input type="hidden" name="date_to" id="date_to" value="{{date("Y-m-t")}}">
                    <button class="btn btn-glow-dark btn-dark mb-0" type="submit">
                        Текущий месяц
                    </button>
            </form>

            <form action="?token={{ $company->token }}" method="GET" class="d-inline">
                    <input type="hidden" name="date_from" id="date_from" value="{{date("Y-m-01", strtotime("-1 months"))}}">
                    <input type="hidden" name="date_to" id="date_to" value="{{date("Y-m-t", strtotime("-1 months"))}}">
                    <button class="btn btn-glow-dark btn-dark mb-0" type="submit">
                        Прошлый месяц
                    </button>
            </form>
        </div>

        {{--
            Second element (right floated)
            Other filterd
        --}}
        <div>
            <a class="dashed-link text-muted" href='{{ route(Request::route()->getName()) }}'>
                Сбросить фильтры
            </a>
            <br>
            <a class="dashed-link text-muted" data-toggle="collapse" href="#moreFilters" id="moreFiltersLink">
                Показать фильтры
            </a>
        </div>
    </div>
    {{-- Other filters block --}}
    <div class="collapse" id="moreFilters">
        <div class="card-body">
            <div class="col-sm-8 col-md-5 p-0">
                <form action="?token={{ $company->token }}" method="GET">
                    @csrf
                    <div class="input-daterange input-group" id="datepicker_range">
                        <input type="text" class="form-control text-left date-picker" placeholder="Начальная дата" name="date_from" value="{{ request('date_from') }}" autocomplete="off">
                        <input type="text" class="form-control text-left date-picker" placeholder="Конечная дата" name="date_to" value="{{ request('date_to') }}" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-icon btn-dark">
                                <i class="feather icon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

