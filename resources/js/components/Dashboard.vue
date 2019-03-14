<template>
    <div>
        <div class="header pb-8 pt-5 pt-md-8" style="background-color: #04703e">
            <div class="container-fluid">
                <div class="header-body">
                    <!-- Card stats -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Traffic</h5>
                                            <span class="h2 font-weight-bold mb-0">350,897</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">New users</h5>
                                            <span class="h2 font-weight-bold mb-0">2,356</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last week</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Sales</h5>
                                            <span class="h2 font-weight-bold mb-0">924</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                        <span class="text-nowrap">Since yesterday</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Performance</h5>
                                            <span class="h2 font-weight-bold mb-0">49,65%</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                                <i class="fas fa-percent"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addVehicleModal" >Add Vehicle</button>
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
                                        <th scope="col">Capacity</th>
                                        <th scope="col">Indicator</th>
                                        <th scope="col">Goods</th>
                                        <th scope="col">Allowed total weight</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">Based trucks</th>
                                        <th scope="col">Contract</th>
                                        <th scope="col">Document</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Validity start date</th>
                                        <th scope="col">Validity end date</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(vehicle, v) in vehicles" v-bind:key="v">
                                        <th scope="row">{{ vehicle.id }}</th>
                                        <td>{{ vehicle.plate_number }}</td>
                                        <td>{{ vehicle.category_id }}</td>
                                        <td>{{ vehicle.capacity_id }}</td>
                                        <td>{{ vehicle.indicator_id }}</td>
                                        <td>{{ vehicle.good_id }}</td>
                                        <td>{{ vehicle.allowed_total_weight }}</td>
                                        <td>{{ vehicle.remarks }}</td>
                                        <td>{{ vehicle.based_truck_id }}</td>
                                        <td>{{ vehicle.contract_id }}</td>
                                        <td>{{ vehicle.document_id }}</td>
                                        <td>{{ vehicle.user_id }}</td>
                                        <td>{{ vehicle.validity_start_date }}</td>
                                        <td>{{ vehicle.validity_end_date }}</td>
                                        <td>{{ vehicle.date }}</td>
                                        <td>{{ vehicle.time }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- View Simulate Modal -->
        <div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Add Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Plate number</label> 
                                <input type="text" id="plate_number" class="form-control" v-model="vehicle.plate_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Category</label> 
                                <input type="text" id="category" class="form-control" v-model="vehicle.category_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Capacity</label> 
                                <input type="text" id="capacity" class="form-control" v-model="vehicle.capacity_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Indicator</label> 
                                <input type="text" id="indicator" class="form-control" v-model="vehicle.indicator_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Goods</label> 
                                <input type="text" id="goods" class="form-control" v-model="vehicle.good_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Allowed total weight</label> 
                                <input type="text" id="allowed_total_weight" class="form-control" v-model="vehicle.allowed_total_weight">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Remarks</label> 
                                <input type="text" id="remarks" class="form-control" v-model="vehicle.remarks">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Based trucks</label> 
                                <input type="text" id="based_trucks" class="form-control" v-model="vehicle.based_truck_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Contract</label> 
                                <input type="text" id="contract" class="form-control" v-model="vehicle.contract_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Document</label> 
                                <input type="text" id="document" class="form-control" v-model="vehicle.document_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">User</label> 
                                <input type="text" id="user" class="form-control" v-model="vehicle.user_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Validity start date</label> 
                                <input type="text" id="validity_start_date" class="form-control" v-model="vehicle.validity_start_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Validity end date</label> 
                                <input type="text" id="validity_end_date" class="form-control" v-model="vehicle.validity_end_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Date</label> 
                                <input type="text" id="date" class="form-control" v-model="vehicle.date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Time</label> 
                                <input type="text" id="time" class="form-control" v-model="vehicle.time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="check_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addVehicle(vehicle)">Save</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            vehicles: [],
            vehicle: [],
            errors: [] 
        }
    },
    created(){
        this.fetchVehicles();
    },
    methods:{
        fetchVehicles(){
            axios.get('/vehicle')
            .then(response => { 
                this.vehicles = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        addVehicle(vehicle){
            axios.post('/vehicle',{
                plate_number: vehicle.plate_number,
                category_id: vehicle.category_id,
                capacity_id: vehicle.capacity_id,
                indicator_id: vehicle.indicator_id,
                good_id: vehicle.good_id,
                allowed_total_weight: vehicle.allowed_total_weight,
                remarks: vehicle.remarks,
                based_truck_id: vehicle.based_truck_id,
                contract_id: vehicle.contract_id,
                document_id: vehicle.document_id,
                user_id: vehicle.user_id,
                validity_start_date: vehicle.validity_start_date,
                validity_end_date: vehicle.validity_end_date,
                date: vehicle.date,
                time: vehicle.time
            })
            .then(response =>{
                this.vehicles[0].push = response.data;
            })
            .catch(error => {   
                this.errors = error.response.errors;
            })
        }
    }
}
</script>
