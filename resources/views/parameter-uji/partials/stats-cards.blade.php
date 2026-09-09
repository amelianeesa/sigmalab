                <!-- Limit Data Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Statistik Base (Konstan)
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800" title="Dihitung dari 20 data historis pertama">
                                        Mean (µ): {{ number_format($selectedParameter->mean ?? 0, 4) }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        SD (σ): {{ number_format($selectedParameter->sd ?? 0, 4) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
