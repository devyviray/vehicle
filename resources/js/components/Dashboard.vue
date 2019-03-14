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
                            <div class="row align-items-center">
                                <div class="col-xl-4 mb-2 mt-3 float-right">
                                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="name">
                                </div> 
                            </div>
                        </div>
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
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
                                    <tr v-for="(vehicle, v) in filteredQueues" v-bind:key="v">
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="javascript.void(0)" data-toggle="modal" data-target="#editVehicleModal" @click="copyObject(vehicle)">Edit</a>
                                                    <a class="dropdown-item" href="javascript.void(0)" data-toggle="modal" data-target="#deleteVehicleModal" @click="getVehicleId(vehicle.id)">Delete</a>
                                                </div>
                                            </div>
                                        </td>
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
                        <div class="row mb-3 mt-3 ml-1">
                            <div class="col-6">
                                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{ filteredQueues.length }} Tsr(s)</span>
                            </div>
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

        <!-- Edit Vehicle Modal -->
        <div class="modal fade" id="editVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Edit Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Plate number</label> 
                                <input type="text" id="plate_number" class="form-control" v-model="vehicle_copied.plate_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Category</label> 
                                <input type="text" id="category" class="form-control" v-model="vehicle_copied.category_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Capacity</label> 
                                <input type="text" id="capacity" class="form-control" v-model="vehicle_copied.capacity_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Indicator</label> 
                                <input type="text" id="indicator" class="form-control" v-model="vehicle_copied.indicator_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Goods</label> 
                                <input type="text" id="goods" class="form-control" v-model="vehicle_copied.good_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Allowed total weight</label> 
                                <input type="text" id="allowed_total_weight" class="form-control" v-model="vehicle_copied.allowed_total_weight">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Remarks</label> 
                                <input type="text" id="remarks" class="form-control" v-model="vehicle_copied.remarks">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Based trucks</label> 
                                <input type="text" id="based_trucks" class="form-control" v-model="vehicle_copied.based_truck_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Contract</label> 
                                <input type="text" id="contract" class="form-control" v-model="vehicle_copied.contract_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Document</label> 
                                <input type="text" id="document" class="form-control" v-model="vehicle_copied.document_id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">User</label> 
                                <input type="text" id="user" class="form-control" v-model="vehicle_copied.user_id">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Validity start date</label> 
                                <input type="text" id="validity_start_date" class="form-control" v-model="vehicle_copied.validity_start_date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Validity end date</label> 
                                <input type="text" id="validity_end_date" class="form-control" v-model="vehicle_copied.validity_end_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Date</label> 
                                <input type="text" id="date" class="form-control" v-model="vehicle_copied.date">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Time</label> 
                                <input type="text" id="time" class="form-control" v-model="vehicle_copied.time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="check_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="editVehicle(vehicle_copied)">Save</button>
                </div>
                </div>
            </div>
        </div>

        <!-- Delete Vehicle Modal -->
        <div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this Vehicle?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteVehicle">Delete</button>
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
            vehicle_copied: [],
            vehicle_id: '',
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            keywords: '',
        }
    },
    created(){
        this.fetchVehicles();
    },
    methods:{
        getVehicleId(id){
            this.vehicle_id = id;
        },
        copyObject(vehicle){
            this.vehicle_copied = Object.assign({}, vehicle)
        },
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
                $('#addVehicleModal').modal('hide');
                alert('Vehicle successfully added');
                this.vehicles.unshift(response.data);
            })
            .catch(error => {   
                this.errors = error.response.errors;
            })
        },
        editVehicle(vehicle){
            var index = this.vehicles.findIndex(item => item.id == vehicle.id);
            axios.patch(`/vehicle/${vehicle.id}`,{
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
            .then(response => {
                $('#editVehicleModal').modal('hide');
                alert('Vehicle successfully updated');
                this.vehicles.splice(index,1,response.data);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        deleteVehicle(){
            var index = this.vehicles.findIndex(item => item.id == this.vehicle_id);
            axios.delete(`/vehicle/${this.vehicle_id}`)
            .then(response => {
                $('#deleteVehicleModal').modal('hide');
                alert('Vehicle successfully deleted');
                this.vehicles.splice(index,1);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        setPage(pageNumber) {
            this.currentPage = pageNumber;
        },

        resetStartRow() {
            this.currentPage = 0;
        },

        showPreviousLink() {
            return this.currentPage == 0 ? false : true;
        },

        showNextLink() {
            return this.currentPage == (this.totalPages - 1) ? false : true;
        }   
    },
    computed:{
        filteredVehicles(){
            let self = this;
            return Object.values(self.vehicles).filter(vehicle => {
                return vehicle.plate_number.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(Object.values(this.vehicles).length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredVehicles.slice(index, index + this.itemsPerPage);

            if(this.currentPage >= this.totalPages) {
                this.currentPage = this.totalPages - 1
            }

            if(this.currentPage == -1) {
                this.currentPage = 0;
            }

            return queues_array;
        },
    }
}
</script>
