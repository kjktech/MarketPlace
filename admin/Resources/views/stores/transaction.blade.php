@extends('panel::layouts.master-market')

@section('content')
<section class="seller-profile">
    
    <div class="seller-profile__started seller__dashboard-wrapper">
        <div class="user-personal__top pt-2">
            <nav class="h-auto">
                <div class="nav seller__dashboard-chart-tab-wrapper" id="nav-tab" role="tablist">
                    <a class="nav-item seller__dashboard-chart-tab seller-add-product__categories-buttontext-capitalize active"
                       id="nav-transaction-overview-tab" data-toggle="tab" href="#nav-transaction-overview" role="tab"
                       aria-controls="nav-transaction-overview" aria-selected="true"><span class="sales-summary-tab">Transaction
                            Overview</span>
                        <hr class="seller__dashboard-chart-tab-line user-personal__heading-divider">
                    </a>
                    <a class="nav-item seller__dashboard-chart-tab text-capitalize" id="nav-sales-report-tab"
                       data-toggle="tab" href="#nav-sales-report" role="tab" aria-controls="nav-sales-report"
                       aria-selected="false"><span class="your-payment-tab">Sales Report</span>
                        <hr class="seller__dashboard-chart-tab-line user-personal__heading-divider">
                    </a>
                    <a class="nav-item seller__dashboard-chart-tab text-capitalize" id="nav-catalog-performance-tab"
                       data-toggle="tab" href="#nav-catalog-performance" role="tab" aria-controls="nav-catalog-performance"
                       aria-selected="true"><span class="sales-summary-tab">Catalog Performance</span>
                        <hr class="seller__dashboard-chart-tab-line user-personal__heading-divider">
                    </a>
                    <a class="nav-item seller__dashboard-chart-tab text-capitalize" id="nav-account-statement-tab"
                       data-toggle="tab" href="#nav-account-statement" role="tab" aria-controls="nav-account-statement"
                       aria-selected="false"><span class="your-payment-tab">Account Statement</span>
                        <hr class="seller__dashboard-chart-tab-line user-personal__heading-divider">
                    </a>
                </div>
                <hr class="user-personal__heading-divider">
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-transaction-overview" role="tabpanel"
                     aria-labelledby="nav-transaction-overview-tab">
                    <div class="seller-transaction__overview">
                        <div class="d-flex">
                            <div class="seller-transaction__overview-one">
                                <svg class="icon icon-calendar">
                                    <use xlink:href="#icon-calendar"></use>
                                </svg>
                                <span>February 2019</span>
                                <div class="seller-transaction__overview-one-detail">
                                    <div class="seller-transaction__overview-one-detail-table">
                                        <div class="seller-transaction__overview-one-detail-table-row">
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-item">Item Price
                                                    Credit</p>
                                            </div>
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-price">0.00 <span>NGN</span></p>
                                            </div>
                                        </div>
                                        <div class="seller-transaction__overview-one-detail-table-row">
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-item">Item Price</p>
                                            </div>
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-price">0.00 <span>NGN</span></p>
                                            </div>
                                        </div>
                                        <div class="seller-transaction__overview-one-detail-table-row">
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-item">Commission
                                                    Credit</p>
                                            </div>
                                            <div class="seller-transaction__overview-one-detail-table-row-col">
                                                <p class="seller-transaction__overview-one-detail-price">0.00 <span>NGN</span></p>
                                            </div>
                                        </div>
                                        <div class="seller-transaction__overview-one-detail-table-row">
                                            <div class="seller-transaction__overview-one-detail-table-row-col last__col">
                                                <p class="seller-transaction__overview-one-detail-item">Commission</p>
                                            </div>
                                            <div class="seller-transaction__overview-one-detail-table-row-col last__col">
                                                <p class="seller-transaction__overview-one-detail-price">0.00 <span>NGN</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="seller-transaction__overview-one-detail mt-0 border-top-0 bottom__row">
                                    <div class="seller-transaction__overview-one-detail-table">
                                        <div class="seller-transaction__overview-one-detail-table-row">
                                            <div class="seller-transaction__overview-one-detail-table-row-col last__col">
                                                <p class="seller-transaction__overview-one-detail-item">Payout
                                                    Amount</p>
                                            </div>
                                            <div class="seller-transaction__overview-one-detail-table-row-col last__col">
                                                <p class="seller-transaction__overview-one-detail-price">{{ $orders_payout }}
                                                    <span>NGN</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="seller-transaction__overview-two">
                                <div class="seller-transaction__overview-two-heading">
                                    <div class="seller-transaction__overview-two-heading-row">
                                        <div class="seller-transaction__overview-two-heading-row-col">
                                            <p class="seller-transaction__overview-two-heading-text">Main</p>
                                        </div>
                                        <div class="seller-transaction__overview-two-heading-row-col">
                                            <p class="seller-transaction__overview-two-heading-text">Sum</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="seller-transaction__overview-two-table">
                                    @foreach($orders as $order)
                                    <div class="seller-transaction__overview-two-table-row">
                                        <div class="seller-transaction__overview-two-table-row-col">
                                            <p class="seller-transaction__overview-two-table-text">
                                                {{ $order->listing->title }} </p>
                                        </div>
                                        <div class="seller-transaction__overview-two-table-row-col">
                                            <p class="seller-transaction__overview-two-heading-text">{{ $order->price }}</p>
                                        </div>
                                    </div>
                                    @endforeach


                                    <div class="seller-transaction__overview-two-table-row">
                                        <div class="seller-transaction__overview-two-table-row-col">
                                            <p class="seller-transaction__overview-two-table-text"></p>
                                        </div>
                                        <div class="seller-transaction__overview-two-table-row-col">
                                            <p class="seller-transaction__overview-two-heading-text"></p>
                                        </div>
                                    </div>
                                    <div class="seller-transaction__overview-two-table-row">
                                        <div class="seller-transaction__overview-two-table-row-col d-flex w-100">
                                            <p class="seller-transaction__overview-two-table-text mx-auto seller-transaction__overview-two-table-footer-item">Item List</p>
                                            <p class="seller-transaction__overview-two-table-text mx-auto seller-transaction__overview-two-table-footer-item">Commission</p>
                                        </div>
                                        <div class="seller-transaction__overview-two-table-row-col">
                                            <p class="seller-transaction__overview-two-heading-text mx-auto seller-transaction__overview-two-table-footer-item">Payment
                                                Amount</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="seller-transaction__table-wrap">
                            <div class="seller-transaction__table-filter">
                                <span class="seller-transaction__table-filter-text">Filter:</span>
                                <button class="btn seller-transaction__table-filter-button">Transactions from last
                                    week</button>
                                <button class="btn seller-transaction__table-filter-button">Transactions from last
                                    month</button>
                            </div>

                            <div class="seller-transaction__table">
                                <div class="seller-transaction__table-row">
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Date</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Transaction Type</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Transaction Number</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Order Number</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Details</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Amount</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">VAT</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">Payment Status</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content-heading">email</p>
                                    </div>
                                </div>
                                @foreach($orders as $order)
                                    <div class="seller-transaction__table-row">
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">{{ $order->created_at->toFormattedDateString() }}</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">Buy</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">{{ $order->order->reference }}</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">{{ $order->order->id }}</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content"></p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">{{ $order->order->amount }}</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">0</p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">
                                            @if($order->order->status == 'paid')
                                             Paid
                                            @else
                                             Pending
                                            @endif
                                        </p>
                                    </div>
                                    <div class="seller-transaction__table-row-col">
                                        <p class="seller-transaction__table-content">{{ $order->email }}</p>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="seller__dashboard-content-navigate">
                            <div class="d-flex ml-auto">
                                <span class="seller-order__content-paging">1</span>
                                <span class="seller-order__content-paging">of</span>
                                <span class="seller-order__content-paging">10</span>
                                <div class="seller__dashboard-announcement-modal-previous">
                                    <svg class="icon icon-arrow-next">
                                        <use xlink:href="#icon-arrow-next"></use>
                                    </svg>
                                </div>
                                <div class="seller__dashboard-announcement-modal-next active-modal">
                                    <svg class="icon icon-arrow-next">
                                        <use xlink:href="#icon-arrow-next"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>




                <div class="tab-pane fade" id="nav-sales-report" role="tabpanel" aria-labelledby="nav-sales-report-tab">
                    <div class="user-personal__top pt-3">
                        <nav class="h-auto">
                            <span class="seller-transaction__sales-heading">Total Order Count</span>

                            <div class="nav seller__dashboard-chart-tab-wrapper float-right" id="nav-tab" role="tablist">
                                <a class="nav-item seller-order__tab active mr-5 text-capitalize" id="nav-total-order-this-week-tab"
                                   data-toggle="tab" href="#nav-total-order-this-week" role="tab" aria-controls="nav-total-order-this-week"
                                   aria-selected="true"><span class="sales-summary-tab">This week</span>
                                </a>

                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-total-order-last-week-tab"
                                   data-toggle="tab" href="#nav-total-order-last-week" role="tab" aria-controls="nav-total-order-last-week"
                                   aria-selected="false"><span class="your-payment-tab">Last week</span>
                                </a>
                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-total-order-last-month-tab"
                                   data-toggle="tab" href="#nav-total-order-last-month" role="tab" aria-controls="nav-total-order-last-month"
                                   aria-selected="true"><span class="sales-summary-tab">Last month</span>
                                </a>

                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-total-order-last-two-month-tab"
                                   data-toggle="tab" href="#nav-total-order-last-two-month" role="tab"
                                   aria-controls="nav-total-order-last-two-month" aria-selected="false"><span
                                            class="your-payment-tab">Last two months</span>
                                </a>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-total-order-this-week" role="tabpanel"
                                 aria-labelledby="nav-total-order-this-week-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="orderCountThisWeekChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="nav-total-order-last-week" role="tabpanel"
                                 aria-labelledby="nav-total-order-last-week-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="orderCountLastWeekChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-total-order-last-month" role="tabpanel"
                                 aria-labelledby="nav-total-order-last-month-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="orderCountLastMonthChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-total-order-last-two-month" role="tabpanel"
                                 aria-labelledby="nav-total-order-last-two-month-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="orderCountLastTwoMonthsChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="user-personal__top pt-5">
                        <nav class="h-auto">
                            <span class="seller-transaction__sales-heading">Gross Sale Sum</span>

                            <div class="nav seller__dashboard-chart-tab-wrapper float-right" id="nav-tab" role="tablist">
                                <a class="nav-item seller-order__tab active mr-5 text-capitalize" id="nav-gross-sales-this-week-tab"
                                   data-toggle="tab" href="#nav-gross-sales-this-week" role="tab" aria-controls="nav-gross-sales-this-week"
                                   aria-selected="true"><span class="sales-summary-tab">This week</span>
                                </a>

                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-gross-sales-last-week-tab"
                                   data-toggle="tab" href="#nav-gross-sales-last-week" role="tab" aria-controls="nav-gross-sales-last-week"
                                   aria-selected="false"><span class="your-payment-tab">Last week</span>
                                </a>
                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-gross-sales-last-month-tab"
                                   data-toggle="tab" href="#nav-gross-sales-last-month" role="tab" aria-controls="nav-gross-sales-last-month"
                                   aria-selected="true"><span class="sales-summary-tab">Last month</span>
                                </a>

                                <a class="nav-item seller-order__tab mr-5 text-capitalize" id="nav-gross-sales-last-two-month-tab"
                                   data-toggle="tab" href="#nav-gross-sales-last-two-month" role="tab"
                                   aria-controls="nav-gross-sales-last-two-month" aria-selected="false"><span
                                            class="your-payment-tab">Last two months</span>
                                </a>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-gross-sales-this-week" role="tabpanel"
                                 aria-labelledby="nav-gross-sales-this-week-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="grossSalesThisWeekChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="nav-gross-sales-last-week" role="tabpanel"
                                 aria-labelledby="nav-gross-sales-last-week-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="grossSalesLastWeekChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-gross-sales-last-month" role="tabpanel"
                                 aria-labelledby="nav-gross-sales-last-month-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="grossSalesLastMonthChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="nav-gross-sales-last-two-month" role="tabpanel"
                                 aria-labelledby="nav-gross-sales-last-two-month-tab">
                                <div class="seller-transaction__catalog-wrap p-0 pb-4">
                                    <div class="seller__dashboard-chart-wrapper">
                                        <canvas id="grossSalesLastTwoMonthsChart" width="500" class="w-100"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>



                <div class="tab-pane fade" id="nav-catalog-performance" role="tabpanel" aria-labelledby="nav-catalog-performance-tab">
                    <h3 class="seller-transaction__catalog-heading">Summary</h3>
                    <div class="seller-transaction__catalog-wrap">
                        <div class="seller-transaction__catalog-row">
                            <p class="seller-transaction__catalog-row-heading">Last 30 days</p>
                            <div class="seller-transaction__catalog-row-content">
                                <div class="seller-transaction__catalog-row-content-row-heading">
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Gross Sales</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Gross Items Sold</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Missed Sales</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Top Perforemers</p>
                                    </div>
                                </div>
                                <div class="seller-transaction__catalog-row-content-row">
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext">0 %</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext">0 %</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext">0 %</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="seller-transaction__catalog-row mt-5 mb-2">
                            <div class="seller-transaction__catalog-row-heading-wrap">
                                <p class="seller-transaction__catalog-row-heading mr-auto">Trending</p>
                                <div class="seller-transaction__catalog-row-heading-select-wrap">
                                    <label for="time-range">Time Range</label>
                                    <select name="time-rande">
                                        <option value="Last 7 days">Last 7 days</option>
                                        <option value="Last 14 days">Last 14 days</option>
                                        <option value="Last 2 months">Last 2 months</option>
                                        <option value="Last 3 months">Last 3 months</option>
                                    </select>
                                </div>
                                <div class="seller-transaction__catalog-row-heading-search-wrap">
                                    <input type="text" placeholder="Brand or Category">
                                    <svg class="icon icon-search-icon">
                                        <use xlink:href="#icon-search-icon"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="seller-transaction__catalog-row-content">
                                <div class="seller-transaction__catalog-row-content-row-heading">
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Gross Sales</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Gross Items Sold</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Missed Sales</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-heading-col">
                                        <p class="seller-transaction__catalog-row-content-heading">Catalog</p>
                                    </div>
                                </div>
                                <div class="seller-transaction__catalog-row-content-row">
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext"><span>0</span>%</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext"><span>0</span>%</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0 NGN</p>
                                        <p class="seller-transaction__catalog-row-content-subtext"><span>0</span>%</p>
                                    </div>
                                    <div class="seller-transaction__catalog-row-content-row-col">
                                        <p class="seller-transaction__catalog-row-content-text">0</p>
                                        <p class="seller-transaction__catalog-row-content-subtext"><span>0</span>%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-account-statement" role="tabpanel" aria-labelledby="nav-account-statement-tab">
                    <div class="seller-transaction__catalog-wrap">
                        <div class="seller-transaction__catalog-row">
                            <p class="seller-transaction__catalog-row-heading">Account Statement</p>
                            <div class="seller-transaction__account-row-content">
                                <div class="seller-transaction__account-row-content-row">
                                    <div class="seller-transaction__account-row-content-row-col">
                                        <p class="seller-transaction__account-row-content-subtext">Due & Unpaid</p>
                                        <p class="seller-transaction__account-row-content-text">0 NGN</p>
                                    </div>
                                    <div class="seller-transaction__account-row-content-row-col">
                                        <p class="seller-transaction__account-row-content-subtext">Open Statement</p>
                                        <p class="seller-transaction__account-row-content-text">0 NGN</p>
                                    </div>
                                    <div class="seller-transaction__account-row-content-row-col">
                                        <p class="seller-transaction__account-row-content-subtext">Paid in last 3
                                            Months</p>
                                        <p class="seller-transaction__account-row-content-text">0 NGN</p>
                                    </div>
                                </div>
                            </div>


                            <div class="seller-transaction__account-overview">
                                <div class="seller-transaction__account-overview-small">
                                    <div class="seller-transaction__account-overview-small-guide-wrap">
                                        <ul class="seller-transaction__account-overview-small-guide">
                                            <li>All</li>
                                            <li><span>Open</span></li>
                                            <li><span>Paid</span></li>
                                            <li><span>Unpaid</span></li>
                                        </ul>
                                    </div>
                                    <div class="seller-transaction__account-overview-small-detail">
                                        <div class="seller-transaction__account-overview-small-detail-heading">
                                            <p>Period</p>
                                            <p class="ml-auto">Payout</p>
                                        </div>

                                        <div class="seller-transaction__account-overview-small-detail-table">
                                            <div class="seller-transaction__account-overview-small-detail-table-row active">
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col active">
                                                    <p class="transaction__date">Mon, 21-01-2019 - Tue, 29-01-2019</p>
                                                    <p class="transaction__info">Current Statement</p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__status open"></p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__amount">0 NGN</p>
                                                </div>
                                            </div>

                                            <div class="seller-transaction__account-overview-small-detail-table-row">
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__date">Mon, 21-01-2019 - Tue, 29-01-2019</p>
                                                    <p class="transaction__info">AF0001-070219</p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__status unpaid"></p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__amount">0 NGN</p>
                                                </div>
                                            </div>

                                            <div class="seller-transaction__account-overview-small-detail-table-row">
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__date">Mon, 21-01-2019 - Tue, 29-01-2019</p>
                                                    <p class="transaction__info">Account Statement No</p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__status paid"></p>
                                                </div>
                                                <div class="seller-transaction__account-overview-small-detail-table-row-col">
                                                    <p class="transaction__amount">0 NGN</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="seller-transaction__account-overview-big">
                                    <div class="seller-transaction__account-overview-big-heading-table">
                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="main__heading">Period</p>
                                                <p class="sub__heading">Mon, 21-01-2019 - Tue, 29-01-2019</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="main__heading">STATEMENET NO.</p>
                                                <p class="sub__heading">AF0001-070219</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="main__heading">Status</p>
                                                <p class="transaction__status open"></p>
                                                <p class="sub__heading">Open</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="table__line">
                                    <div class="seller-transaction__account-overview-big-heading-table">
                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Opening
                                                    Balance</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-statement">Negative
                                                    closing balance from previous statements</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-status">0.00
                                                    NGN</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="seller-transaction__account-overview-big-heading-table">
                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Orders</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col content__table">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement">Sales
                                                                Revenue</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement">Other
                                                                Revenue</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement">Other
                                                                Fees</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="table__line mt-0 mb-1">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement subtotal">Subtotal</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status sumtotal">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Refunds</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col content__table">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement">Returned
                                                                or Cancelled orders</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement">Refund
                                                                on fees</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="table__line mt-0 mb-1">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement subtotal">Subtotal</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status sumtotal">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>



                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Others</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col content__table">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement"></p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status"></p>
                                                        </div>
                                                    </div>
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement"></p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="table__line mt-0 mb-1">
                                                <div class="seller-transaction__account-overview-big-content-table">
                                                    <div class="seller-transaction__account-overview-big-content-table-row">
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-statement subtotal">Subtotal</p>
                                                        </div>
                                                        <div class="seller-transaction__account-overview-big-content-table-row-col">
                                                            <p class="seller-transaction__account-overview-big-content-status sumtotal">0.00
                                                                NGN</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <hr class="table__line">

                                    <div class="seller-transaction__account-overview-big-heading-table">
                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Closing
                                                    Balance</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-statement closing__balance">Total
                                                    Balance</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-status closing__balance">0.00
                                                    NGN</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="table__line color__change">
                                    <div class="seller-transaction__account-overview-big-heading-table">
                                        <div class="seller-transaction__account-overview-big-heading-table-row">
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-period">Payout</p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-statement closing__balance"></p>
                                            </div>
                                            <div class="seller-transaction__account-overview-big-heading-table-row-col">
                                                <p class="seller-transaction__account-overview-big-content-status closing__balance">0.00
                                                    NGN</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="seller-transaction__account-overview-big-wrap">
                                        <button class="btn seller-add-product__form-button-preview mx-auto">Download
                                            all
                                            Transactions</button>
                                        <button class="btn seller-add-product__form-button-preview mx-auto">Print
                                            Account
                                            Statement</button>
                                        <button class="btn seller-add-product__form-button-preview mx-auto continue">Sales
                                            Report</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <div class="d-flex mb-2">
                                <h3 class="seller-order__content-export mr-auto">Exports</h3>
                                <div class="seller-transaction__catalog-row-heading-search-wrap">
                                    <input type="text">
                                    <svg class="icon icon-search-icon">
                                        <use xlink:href="#icon-search-icon"></use>
                                    </svg>
                                </div>
                            </div>

                            <div class="seller-order__content-table-head-wrapper">
                                <div class="seller-order__content-export-table-head">
                                    <p>#</p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p>Type</p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p>Time Requested</p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p>Time Completed</p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p>Status</p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p>Download</p>
                                </div>
                            </div>

                            <div class="seller-order__content-table-head-wrapper seller-order__content-table-rows">
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                                <div class="seller-order__content-export-table-head">
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <div class="seller__dashboard-content-navigate">
                            <div class="d-flex ml-auto">
                                <span class="seller-order__content-paging">1</span>
                                <span class="seller-order__content-paging">of</span>
                                <span class="seller-order__content-paging">10</span>
                                <div class="seller__dashboard-announcement-modal-previous">
                                    <svg class="icon icon-arrow-next">
                                        <use xlink:href="#icon-arrow-next"></use>
                                    </svg>
                                </div>
                                <div class="seller__dashboard-announcement-modal-next active-modal">
                                    <svg class="icon icon-arrow-next">
                                        <use xlink:href="#icon-arrow-next"></use>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
</section>

<script src="{{ asset('themes/' .  current_theme()  . '/seller/assets/js/Chart.min.js') }}"></script>

<script>
    $('#close-pop-message').click(function () {
        $('.seller__dashboard-pop-message').addClass('d-none');
    })
</script>
<!-- Total order count charts -->

<!-- this week -->
<script>
    var ctx = document.getElementById("orderCountThisWeekChart").getContext('2d');
    var orderCountThisWeekChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon, 21-01-2019", "Tue, 22-01-2019", "Wed, 23-01-2019", "Thur, 24-01-2019",
                "Fri, 25-01-2019"
            ],
            datasets: [{
                label: 'This Week',
                data: [12, 6, 0, 3, 5, 21, 8],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- last week -->
<script>
    var ctx = document.getElementById("orderCountLastWeekChart").getContext('2d');
    var orderCountLastWeekChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon, 21-01-2019", "Tue, 22-01-2019", "Wed, 23-01-2019", "Thur, 24-01-2019",
                "Fri, 25-01-2019"
            ],
            datasets: [{
                label: 'Last Week',
                data: [12, 18, 3, 5, 2, 6, 0],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- Last Month -->
<script>
    var ctx = document.getElementById("orderCountLastMonthChart").getContext('2d');
    var orderCountLastMonthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["1-01-2019", "05-01-2019", "10-01-2019", "15-01-2019",
                "20-01-2019", "25-01-2019", "30-01-2019"
            ],
            datasets: [{
                label: 'Last Month',
                data: [12, 6, 0, 3, 5, 21, 8],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- Last two Months -->
<script>
    var ctx = document.getElementById("orderCountLastTwoMonthsChart").getContext('2d');
    var orderCountLastTwoMonthsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["1-01-2019", "10-01-2019", "20-01-2019", "30-01-2019",
                "1-02-2019", "10-02-2019", "20-02-2019", "30-02-2019"
            ],
            datasets: [{
                label: 'Last Two Months',
                data: [12, 6, 0, 3, 5, 21, 8, 18],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>




<!-- Gross sales sum charts -->
<!-- this week -->
<script>
    var ctx = document.getElementById("grossSalesThisWeekChart").getContext('2d');
    var grossSalesThisWeekChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon, 21-01-2019", "Tue, 22-01-2019", "Wed, 23-01-2019", "Thur, 24-01-2019",
                "Fri, 25-01-2019"
            ],
            datasets: [{
                label: 'This Week',
                data: [12, 6, 0, 3, 5, 21, 8],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- last week -->
<script>
    var ctx = document.getElementById("grossSalesLastWeekChart").getContext('2d');
    var grossSalesLastWeekChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon, 21-01-2019", "Tue, 22-01-2019", "Wed, 23-01-2019", "Thur, 24-01-2019",
                "Fri, 25-01-2019"
            ],
            datasets: [{
                label: 'Last Week',
                data: [12, 18, 3, 5, 2, 6, 0],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- Last Month -->
<script>
    var ctx = document.getElementById("grossSalesLastMonthChart").getContext('2d');
    var grossSalesLastMonthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["1-01-2019", "05-01-2019", "10-01-2019", "15-01-2019",
                "20-01-2019", "25-01-2019", "30-01-2019"
            ],
            datasets: [{
                label: 'Last Month',
                data: [12, 6, 0, 3, 5, 21, 8],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>

<!-- Last two Months -->
<script>
    var ctx = document.getElementById("grossSalesLastTwoMonthsChart").getContext('2d');
    var grossSalesLastTwoMonthsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["1-01-2019", "10-01-2019", "20-01-2019", "30-01-2019",
                "1-02-2019", "10-02-2019", "20-02-2019", "30-02-2019"
            ],
            datasets: [{
                label: 'Last Two Months',
                data: [12, 6, 0, 3, 5, 21, 8, 18],
                backgroundColor: 'rgba(230, 183, 18,0.1)',
                borderColor: "#E6B712",
                borderWidth: "5",
                defaultFontFamily: "Montserrat",
            }]
        },

        options: {
            animation: {
                duration: 0, // general animation time
            },
            hover: {
                animationDuration: 0, // duration of animations when hovering an item
            },
            responsiveAnimationDuration: 0, // animation duration after a resize
        }
    });
</script>
@stop
