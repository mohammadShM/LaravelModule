@extends('Dashboard::master')
@section('breadcrumb')
    <li><a href="{{ route('payments.index') }}" title="تراکنش ها">تراکنش ها</a></li>
@endsection
@section('content')
    <div class="row no-gutters  margin-bottom-10">
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش ۳۰ روز گذشته سایت </p>
            <p>{{ number_format($last30DaysTotal) }}تومان </p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>درامد خالص ۳۰ روز گذشته سایت</p>
            <p>{{ number_format($last30DaysBenefit) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-left-10 margin-bottom-10">
            <p>کل فروش سایت</p>
            <p>{{ number_format($totalSell) }} تومان</p>
        </div>
        <div class="col-3 padding-20 border-radius-3 bg-white margin-bottom-10">
            <p> کل درآمد خالص سایت</p>
            <p>{{ number_format($totalBenefit) }} تومان</p>
        </div>
    </div>
    <div class="row no-gutters border-radius-3 font-size-13">
        <div class="col-12 bg-white padding-30 margin-bottom-20">
            <figure class="highcharts-figure">
                <div id="container"></div>
            </figure>
        </div>

    </div>
    <div class="row no-gutters  ">
        <div class="col-12 margin-left-10 margin-bottom-15 border-radius-3">
            <p class="box__title">تراکنش ها</p>
            <div class="border-2"/>
            <div class="table__box">
                <table class="table h_line">
                    <thead role="rowgroup">
                    <tr role="row" class="title-row">
                        <th>شناسه</th>
                        <th>نام و نام خانوادگی</th>
                        <th>ایمیل پرداخت کننده</th>
                        <th>مبلغ (تومان)</th>
                        <th>درآمد مدرس</th>
                        <th>درآمد سایت</th>
                        <th>نام دوره</th>
                        <th>تاریخ و ساعت</th>
                        <th>وضعیت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr role="row" class="">
                            <td><a href="">{{ $payment->id }}</a></td>
                            <td>{{ $payment->buyer->name }}</td>
                            <td>{{ $payment->buyer->email }}</td>
                            <td>{{ $payment->amount }}</td>
                            <td>{{ $payment->seller_share }}</td>
                            <td>{{ $payment->site_share }}</td>
                            <td>{{ $payment->paymentable->title }}</td>
                            <td>{{ $payment->created_at }}</td>
                            <td class="@if($payment->status == \Mshm\Payment\Models\Payment::STATUS_SUCCESS)
                                text-success @elseif($payment->status == \Mshm\Payment\Models\Payment::STATUS_FAIL)
                                text-error @else text-yellow @endif">
                                @Lang($payment->status)
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        @include('Common::layouts.feedbacks')
    </script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <!--suppress JSUnresolvedVariable -->
    <script>
        Highcharts.chart('container', {
            title: {
                text: 'نمودار فروش 30 روز گذشته',
                useHTML: true,
                style: {
                    fontSize: '20px',
                    fontFamily: 'tahoma',
                    description: 'rtl',
                }
            },
            tooltip: {
                useHTML: true,
                style: {
                    fontSize: '20px',
                    fontFamily: 'tahoma',
                    description: 'rtl',
                },
                formatter: function () {
                    return (this.x ? "تاریخ : " + this.x + "<br>" : '') +
                        "مبلغ : " + this.y.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "تومان";
                }
            },
            xAxis: {
                categories: [@foreach($last30Days as $day) "{{ $day->format('Y-m-d') }}", @endforeach],
                useHTML: true,
                style: {
                    fontSize: '20px',
                    fontFamily: 'tahoma',
                    description: 'rtl',
                },
            },
            yAxis: {
                title: {
                    "text": "مبلغ",
                    useHTML: true,
                    style: {
                        fontSize: '20px',
                        fontFamily: 'tahoma',
                        description: 'rtl',
                    },
                },
                labels: {
                    formatter: function () {
                        return this.value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + "تومان";
                    },
                    useHTML: true,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'tahoma',
                        description: 'rtl',
                    },
                },
            },
            labels: {
                items: [{
                    html: 'درآمد 30 روز گذشته',
                    style: {
                        left: '50px',
                        top: '18px',
                        color: ( // theme
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'black'
                    }
                }]
            },
            series: [{
                type: 'column',
                name: 'تراکنش موفق',
                data: [@foreach($last30Days as $day) {{ $paymentRepo->getDaySuccessPaymentTotal($day) }}, @endforeach]
            }, {
                type: 'column',
                name: 'درصد سایت',
                data: [@foreach($last30Days as $day) {{ $paymentRepo->getDaySiteShare($day) }}, @endforeach]
            }, {
                type: 'column',
                name: 'درصد مدرس',
                data: [@foreach($last30Days as $day) {{ $paymentRepo->getDaySellerShare($day) }}, @endforeach]
            }, {
                type: 'spline',
                name: 'فروش',
                data: [@foreach($last30Days as $day) {{ $paymentRepo->getDaySuccessPaymentTotal($day) }}, @endforeach],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[3],
                    fillColor: 'white'
                }
            }, {
                type: 'pie',
                name: 'نسبت',
                data: [{
                    name: 'درصد سایت',
                    y: {{ $last30DaysBenefit }},
                    color: Highcharts.getOptions().colors[0]
                }, {
                    name: 'درصد مدرس',
                    y: {{ $last30DaysSellerShare }},
                    color: Highcharts.getOptions().colors[1]
                }],
                center: [100, 80],
                size: 100,
                showInLegend: false,
                dataLabels: {
                    enabled: false
                }
            }
            ]
        });
    </script>
@endsection
