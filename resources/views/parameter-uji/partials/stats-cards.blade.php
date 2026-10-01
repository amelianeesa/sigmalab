
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 border-start border-primary border-4 shadow-sm h-100">
                        <div class="card-body py-2 px-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-uppercase fw-bold text-primary mb-1" style="font-size: 0.7rem;">
                                        Statistik Base (Konstan)
                                    </div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;" title="Dihitung dari 20 data historis pertama">
                                        Mean (µ): {{ number_format($selectedParameter->mean ?? 0, 4) }}
                                    </div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                                        SD (σ): {{ number_format($selectedParameter->sd ?? 0, 4) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calculator fa-2x text-muted opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>