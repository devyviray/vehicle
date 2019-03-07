@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row mt-5">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Vehicle List</h3>
                            </div>
                            <div class="col text-right">
                                <a href="#!" class="btn btn-sm btn-primary">Add Vehicle</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Plate number</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Indicator</th>
                                    <th scope="col">Goods</th>
                                    <th scope="col">Visitors</th>
                                    <th scope="col">Based trucks</th>
                                    <th scope="col">Contract</th>
                                    <th scope="col">Document</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"> /argon/</th>
                                    <td> 4,569 </td>
                                    <td> 340 </td>
                                    <td> <i class="fas fa-arrow-up text-success mr-3"></i> 46,53% </td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th scope="row"> /argon/index.html </th>
                                    <td> 3,985 </td>
                                    <td> 319 </td>
                                    <td> <i class="fas fa-arrow-down text-warning mr-3"></i> 46,53% </td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th scope="row"> /argon/charts.html </th>
                                    <td> 3,513 </td>
                                    <td> 294 </td>
                                    <td> <i class="fas fa-arrow-down text-warning mr-3"></i> 36,49% </td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th scope="row"> /argon/tables.html </th>
                                    <td> 2,050 </td>
                                    <td> 147 </td>
                                    <td> <i class="fas fa-arrow-up text-success mr-3"></i> 50,87% </td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <th scope="row"> /argon/profile.html </th>
                                    <td> 1,795 </td>
                                    <td> 190 </td>
                                    <td> <i class="fas fa-arrow-down text-danger mr-3"></i> 46,53% </td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                    <td>1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush