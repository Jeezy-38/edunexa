@extends('layouts.admin')
@push('title', get_phrase('Dashboard'))
@push('meta')@endpush
@push('css')
@endpush
@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-4 px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Dashboard') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row g-2 g-sm-3 my-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">{{App\Models\Course::count()}}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Courses') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">{{App\Models\Lesson::count()}}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Lessons') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">{{App\Models\Enrollment::count()}}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Enrollment') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">{{App\Models\User::where('role', 'student')->count()}}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Students') }}</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="ol-card card-hover">
                <div class="ol-card-body px-20px py-3">
                    <p class="title card-title-hover fs-18px my-2">{{App\Models\User::where('role', 'instructor')->count()}}</p>
                    <p class="sub-title fs-14px">{{ get_phrase('Number of Instructor') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="ol-card p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="title fs-14px">{{ get_phrase('Admin Revenue This Year') }}</h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <a class="btn-link" href="{{route('admin.revenue')}}" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="{{ get_phrase('Admin Revenue') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                    </div>
                </div>
                <div class="ol-card-body">
                    <canvas id="myChart" class="mw-100 w-100" height="320px"></canvas>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>


    <div class="row my-3">
        <div class="col-md-5">
            <div class="ol-card">
                <div class="ol-card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="title fs-14px">{{get_phrase('Course Status')}}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn-link" href="{{route('admin.courses')}}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="{{ get_phrase('Explore Courses') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center g-30px flex-wrap flex-xl-nowrap justify-content-center">
                        <div class="pie-chart-sm">
                            <canvas id="pie2"></canvas>
                        </div>
                        <div class="pie-chart-sm-details">
                            <ul class="color-info-list">
                                <li>
                                    <span class="info-list-color bg-active"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Active')}}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-upcoming"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Upcoming')}}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-pending"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Pending')}}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-private"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Private')}}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-draft"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Draft')}}</span>
                                </li>
                                <li>
                                    <span class="info-list-color bg-inactive"></span>
                                    <span class="title2 fs-14px">{{get_phrase('Inactive')}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="ol-card" id = 'unpaid-instructor-revenue'>
                <div class="ol-card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="title text-14px mb-3">{{ get_phrase('Pending Requested withdrawal') }}</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn-link" href="{{route('admin.instructor.payout')}}" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="{{ get_phrase('Instructor Payout') }}"><i class="fi-rr-arrow-alt-right"></i></a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <tbody>
                                @php
                                    $payouts = App\Models\Payout::where('status', '!=', 1)->limit(20)->orderBy('id')->get();
                                @endphp
                                @foreach($payouts as $payout)
                                <tr>
                                    <td>
                                        <p class="title fs-14px">{{ get_phrase('Name') }}: {{$payout->user->name}}</p>
                                        <small>{{ get_phrase('Email') }}: <span class="text-muted font-13">{{$payout->user->email}}</span></small>
                                    </td>
                                    <td>
                                        <p class="title fs-14px">{{currency($payout->amount)}}</p>
                                        <small><span class="text-muted font-13">{{ get_phrase('Requested withdrawal amount') }}</span></small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $courses = App\Models\Course::get()->groupBy('status');
        $active = isset($courses['active']) ? $courses['active']->count():0;
        $upcoming = isset($courses['upcoming']) ? $courses['upcoming']->count():0;
        $pending = isset($courses['pending']) ? $courses['pending']->count():0;
        $private = isset($courses['private']) ? $courses['private']->count():0;
        $draft = isset($courses['draft']) ? $courses['draft']->count():0;
        $inactive = isset($courses['inactive']) ? $courses['inactive']->count():0;

    @endphp
@endsection

@push('js')

    {{-- Oliv template start --}}
    <script src="{{ asset('assets/backend/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/chart-js/chart.js') }}"></script>
    {{-- Oliv template end --}}


    <script>
        "use strict";

        function getChartColors() {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            return {
                isDark: isDark,
                text: isDark ? '#cbd5e1' : '#4b5675',
                grid: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)',
                border: isDark ? '#1e293b' : '#ffffff',
                lineColor: '#1B84FF',
                lineBg: isDark ? 'rgba(27, 132, 255, 0.18)' : 'rgba(27, 132, 255, 0.08)'
            };
        }

        const xValues = [0, "January", "February", "March", "April", "May", "June", "July", "August", "September",
            "October", "November", "December"
        ];

        let themeColors = getChartColors();

        const revenueChart = new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    label: "{{get_phrase('Admin revenue')}}",
                    fill: true,
                    tension: 0.35,
                    backgroundColor: themeColors.lineBg,
                    borderColor: themeColors.lineColor,
                    borderWidth: 2.5,
                    pointBackgroundColor: themeColors.lineColor,
                    pointBorderColor: themeColors.border,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    data: <?php print_r(json_encode($monthly_amount)); ?>
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: themeColors.text,
                            font: { family: 'Inter', size: 12, weight: '500' }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: themeColors.grid },
                        ticks: { color: themeColors.text, font: { family: 'Inter', size: 11 } }
                    },
                    y: {
                        grid: { color: themeColors.grid },
                        ticks: { color: themeColors.text, font: { family: 'Inter', size: 11 } }
                    }
                }
            }
        });

        // Pie Chart 2
        const project_progress2 = document.getElementById('pie2');
        const progressData2 = {
            labels: ['Active', 'Upcoming', 'Pending', 'Private', 'Draft', 'Inactive'],
            data: [{{$active}}, {{$upcoming}}, {{$pending}}, {{$private}}, {{$draft}}, {{$inactive}}],
        };
        const barColors = [
            "#10b981", // Active (Emerald Green)
            "#1b84ff", // Upcoming (Blue)
            "#ec4899", // Pending (Pink)
            "#8b5cf6", // Private (Vibrant Purple - stands out in both dark & light mode)
            "#64748b", // Draft (Slate)
            "#475569", // Inactive (Muted Slate)
        ];

        const courseStatusChart = new Chart(project_progress2, {
            type: 'doughnut',
            data: {
                labels: progressData2.labels,
                datasets: [{
                    backgroundColor: barColors,
                    label: ' {{get_phrase("Courses")}}',
                    data: progressData2.data,
                    borderWidth: 3,
                    borderColor: themeColors.border,
                    hoverBorderColor: themeColors.border,
                }],
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });

        // Live update charts on theme toggle
        window.addEventListener('themeChanged', function() {
            const updated = getChartColors();
            revenueChart.data.datasets[0].backgroundColor = updated.lineBg;
            revenueChart.data.datasets[0].pointBorderColor = updated.border;
            if (revenueChart.options.plugins && revenueChart.options.plugins.legend) {
                revenueChart.options.plugins.legend.labels.color = updated.text;
            }
            if (revenueChart.options.scales) {
                if (revenueChart.options.scales.x) {
                    revenueChart.options.scales.x.grid.color = updated.grid;
                    revenueChart.options.scales.x.ticks.color = updated.text;
                }
                if (revenueChart.options.scales.y) {
                    revenueChart.options.scales.y.grid.color = updated.grid;
                    revenueChart.options.scales.y.ticks.color = updated.text;
                }
            }
            revenueChart.update();

            courseStatusChart.data.datasets[0].borderColor = updated.border;
            courseStatusChart.data.datasets[0].hoverBorderColor = updated.border;
            courseStatusChart.update();
        });
    </script>
@endpush
