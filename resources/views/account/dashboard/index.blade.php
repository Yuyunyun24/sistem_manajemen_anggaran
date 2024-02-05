@extends('layouts.account')

@section('title')
    Dashboard - UANGKU
@stop

@section('content')

    <script>

    </script>

    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                <div class="card card-statistic-2">
                    <div class="card-icon shadow-primary {{ $saldo_selama_ini < 0 ? 'bg-danger' : 'bg-primary' }}">
                        <i class="{{ $saldo_selama_ini < 0 ? 'fas fa-exclamation-triangle' : 'fas fa-money-check-alt' }}"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4 class="{{ $saldo_selama_ini < 0 ? 'text-danger' : '' }}">TOTAL ANGGARAN </h4>
                        </div>
                        <div class="card-body" style="font-size: 20px; color: {{ $saldo_selama_ini < 0 ? 'red' : '' }}">
                            Rp {{ number_format($saldo_selama_ini, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>ANGGARAN MASUK</h4>
                            </div>
                            <div class="card-body" style="font-size: 20px">
                                Rp {{ number_format($debit, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="card card-statistic-2">
                        <div class="card-icon shadow-primary bg-primary">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>ANGGARAN KELUAR</h4>
                            </div>
                            <div class="card-body" style="font-size: 20px">
                            Rp {{ number_format($kredit, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-chart-pie"></i> STATISTIK KEUANGAN DALAM 1 TAHUN</h4>
                        </div>

                        <div class="card-body">
                            <div id="container">
                                <canvas id="myChart" style="width:100%;max-width:800px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script>
        const xValues = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    label: 'Debit',
                    data: [/* Debit data per month */],
                    borderColor: "green",
                    fill: false
                },{
                    label: 'Kredit',
                    data: [/* Kredit data per month */],
                    borderColor: "red",
                    fill: false
                }]
            },
            options: {
                legend: { display: true },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <?php
        // Query database untuk mendapatkan data debit dan kredit per bulan
        $debit_data = DB::table('debit')
            ->selectRaw('SUM(nominal) as nominal, MONTH(debit_date) as month')
            ->whereYear('debit_date', date('Y'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('month')
            ->pluck('nominal', 'month')
            ->toArray();

        $kredit_data = DB::table('credit')
            ->selectRaw('SUM(nominal) as nominal, MONTH(credit_date) as month')
            ->whereYear('credit_date', date('Y'))
            ->where('user_id', Auth::user()->id)
            ->groupBy('month')
            ->pluck('nominal', 'month')
            ->toArray();

        // Inisialisasi data untuk grafik
        $debit_chart_data = array_fill(1, 12, 0);
        $kredit_chart_data = array_fill(1, 12, 0);

        foreach ($debit_data as $month => $nominal) {
            $debit_chart_data[$month] = $nominal;
        }

        foreach ($kredit_data as $month => $nominal) {
            $kredit_chart_data[$month] = $nominal;
        }

        // Saldo bulan ini, bulan lalu, dan selama ini tetap sama
        $currentMonth = date('n');
        $saldo_bulan_ini = $debit_chart_data[$currentMonth] - $kredit_chart_data[$currentMonth];
        $saldo_bulan_lalu = $debit_chart_data[$currentMonth - 1] - $kredit_chart_data[$currentMonth - 1];
        $saldo_selama_ini = array_sum($debit_chart_data) - array_sum($kredit_chart_data);
        $debit = array_sum($debit_chart_data);
        $kredit = array_sum($kredit_chart_data);

        // Mengonversi array data ke dalam format yang dapat digunakan oleh Chart.js
        $debit_chart_data = array_values($debit_chart_data);
        $kredit_chart_data = array_values($kredit_chart_data);
    ?>

    <script>
        // Mengisi data pada Chart.js dengan data dari database
        const myChart = new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    label: 'Debit',
                    data: <?php echo json_encode($debit_chart_data); ?>,
                    borderColor: "green",
                    fill: false
                },{
                    label: 'Kredit',
                    data: <?php echo json_encode($kredit_chart_data); ?>,
                    borderColor: "red",
                    fill: false
                }]
            },
            options: {
                legend: { display: true },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
