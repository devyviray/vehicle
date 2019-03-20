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
                                            <!-- <h5 class="card-title text-uppercase text-muted mb-0">Traffic</h5>
                                            <span class="h2 font-weight-bold mb-0">350,897</span> -->
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                                <i class="fas fa-chart-bar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span> -->
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <h5 class="card-title text-uppercase text-muted mb-0">New users</h5>
                                            <span class="h2 font-weight-bold mb-0">2,356</span> -->
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                                <i class="fas fa-chart-pie"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last week</span> -->
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <h5 class="card-title text-uppercase text-muted mb-0">Sales</h5>
                                            <span class="h2 font-weight-bold mb-0">924</span> -->
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <!-- <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                                        <span class="text-nowrap">Since yesterday</span> -->
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <!-- <h5 class="card-title text-uppercase text-muted mb-0">Performance</h5>
                                            <span class="h2 font-weight-bold mb-0">49,65%</span> -->
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                                <i class="fas fa-percent"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                        <span class="text-nowrap">Since last month</span> -->
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
                                    <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addVehicleModal" style="background-color: rgb(4, 112, 62);" @click="resetData()">Add Vehicle</a>
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
                                        <th scope="col">Category</th>
                                        <th scope="col">Plate number</th>
                                        <th scope="col">Plant Indicator</th>
                                        <th scope="col">Plant</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Subcon vendor</th>
                                        <th scope="col">Capacity</th>
                                        <th scope="col">Goods</th>
                                        <th scope="col">Allowed total weight</th>
                                        <th scope="col">Based trucks</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">Contract</th>
                                        <th scope="col">Document</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Validity start date</th>
                                        <th scope="col">Validity end date</th>
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
                                                    <!-- <a class="dropdown-item" href="javascript.void(0)" data-toggle="modal" data-target="#deleteVehicleModal" @click="getVehicleId(vehicle.id)">Delete</a> -->
                                                </div>
                                            </div>
                                        </td>
                                        <td scope="row">{{ vehicle.id }}</td>
                                        <td>{{ vehicle.category.description }}</td>
                                        <td>{{ vehicle.plate_number }}</td>
                                        <td>{{ vehicle.indicator.description }}</td>
                                        <td v-if="vehicle.indicator_id == 2"> ALL PLANT</td>
                                        <td v-else>
                                            <span v-for="(plant, p) in vehicle.plants" :key="p">
                                                {{ plant.name }} <br/>
                                            </span>
                                        </td>
                                        <td>{{ vehicle.vendor.vendor_description_lfug }}</td>
                                        <td v-if="vehicle.subcon_vendor">{{ vehicle.subcon_vendor.vendor_description_lfug }}</td>
                                        <td v-else></td>
                                        <td>{{ vehicle.capacity.description }}</td>
                                        <td v-if="vehicle.good">{{ vehicle.good.description }}</td>
                                        <td v-else></td>
                                        <td v-if="vehicle.allowed_total_weight">{{ vehicle.allowed_total_weight }}</td>
                                        <td v-else></td>
                                        <td>{{ vehicle.based_truck.description }}</td>
                                        <td v-if="vehicle.remarks">{{ vehicle.remarks }}</td>
                                        <td v-else></td>
                                        <td v-if="vehicle.contract">{{ vehicle.contract.code }}</td>
                                        <td v-else></td>
                                        <td>{{ vehicle.document }}</td>
                                        <td>{{ vehicle.user.name }}</td>
                                        <td>{{ vehicle.validity_start_date }}</td>
                                        <td>{{ vehicle.validity_end_date }}</td>
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
                                <span>{{ filteredQueues.length }} Vehicle(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Vehicle Modal -->
        <div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1110px;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Add Vehicle</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="vehicle_added">
                            <strong>Success!</strong> Vehicle succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Category</label> 
                                    <select class="form-control" v-model="vehicle.category_id">
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category_id">The category field is required.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plate Number</label> 
                                    <input type="text" id="plate_number" class="form-control" v-model="vehicle.plate_number">
                                    <span class="text-danger" v-if="errors.plate_number">{{ errors.plate_number[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plant indicator</label> 
                                    <select class="form-control" v-model="vehicle.indicator_id" @change="plantChange">
                                        <option v-for="(indicator,i) in indicators" v-bind:key="i" :value="indicator.id"> {{ indicator.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.indicator_id">The indicator field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_plant_add">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Plant</label> 
                                    <multiselect
                                        v-model="vehicle.plant"
                                        :options="plants"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabelPlant"
                                        placeholder="Select Plant"
                                        id="selected_plant"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.plants">The plant field is required</span>
                                </div>  
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="role">Vendor</label> 
                                <v-select
                                    style="width: 100%" 
                                    v-model="vehicle.vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                >      
                                </v-select>
                                <span class="text-danger" v-if="errors.vendor_id">The contract field is required</span>
                            </div>
                            <div class="col-md-4">
                                <label for="role">Subcon Vendor</label> 
                                <v-select 
                                    style="width: 100%"
                                    v-model="vehicle.subcon_vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                >
                                </v-select>
                                <span class="text-danger" v-if="errors.subcon_vendor_id">The Subcon vendor field is required</span>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Capacity</label>
                                    <select class="form-control" v-model="vehicle.capacity_id">
                                        <option v-for="(capacity,c) in capacities" v-bind:key="c" :value="capacity.id"> {{ capacity.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category_id">The capacity field is required.</span>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Goods</label> 
                                    <select class="form-control" v-model="vehicle.good_id">
                                        <option v-for="(good,g) in goods" v-bind:key="g" :value="good.id"> {{ good.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.good_id">The goods field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Allowed Total Weight</label> 
                                    <input type="text" id="allowed_total_weight" class="form-control" v-model="vehicle.allowed_total_weight">
                                    <span class="text-danger" v-if="errors.allowed_total_weight">{{ errors.allowed_total_weight[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Based Trucks</label> 
                                    <select class="form-control" v-model="vehicle.based_truck_id">
                                        <option v-for="(based_truck,b) in based_trucks" v-bind:key="b" :value="based_truck.id"> {{ based_truck.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.based_truck_id">The based truck is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Contract</label> 
                                    <select class="form-control" v-model="vehicle.contract_id">
                                        <option v-for="(contract,c) in contracts" v-bind:key="c" :value="contract.id"> {{ contract.code + ' - ' + contract.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.contract_id">The contract field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Remarks</label> 
                                    <input type="text" id="remarks" class="form-control" v-model="vehicle.remarks">
                                    <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Document</label>
                                    <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                    <span class="text-danger" v-if="errors.attachments">The attachment is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity Start Date</label> 
                                    <input type="date" id="validity_start_date" class="form-control" v-model="vehicle.validity_start_date">
                                    <span class="text-danger" v-if="errors.validity_start_date">The validity start date is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity End Date</label> 
                                    <input type="date" id="validity_end_date" class="form-control" v-model="vehicle.validity_end_date">
                                    <span class="text-danger" v-if="errors.validity_end_date">{{ errors.validity_end_date[0] }}</span>
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
            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1110px;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Edit Vehicle</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="vehicle_updated">
                            <strong>Success!</strong> Vehicle succesfully updated
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Category</label> 
                                    <select class="form-control" v-model="vehicle_copied.category_id" disabled>
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category_id">The category field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plate Number</label> 
                                    <input type="text" id="plate_number" class="form-control" v-model="vehicle_copied.plate_number" disabled>
                                    <span class="text-danger" v-if="errors.plate_number">{{ errors.plate_number[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plant Indicator</label> 
                                    <select class="form-control" v-model="vehicle_copied.indicator_id" @change="plantChange">
                                        <option v-for="(indicator,i) in indicators" v-bind:key="i" :value="indicator.id"> {{ indicator.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.indicator_id">The indicator field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_plant">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Plant</label> 
                                    <multiselect
                                        v-model="vehicle_copied.plants"
                                        :options="plants"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabelPlant"
                                        placeholder="Select Plant"
                                        id="selected_plant"
                                    >
                                    </multiselect>
                                </div>  
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="role">Vendor</label> 
                                <v-select
                                    style="width: 100%" 
                                    v-model="vehicle_copied.vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                >      
                                </v-select>
                                <span class="text-danger" v-if="errors.vendor_id">The contract field is required</span>
                            </div>
                            <div class="col-md-4">
                                <label for="role">Subcon Vendor</label> 
                                <v-select 
                                    style="width: 100%"
                                    v-model="vehicle_copied.subcon_vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                >
                                </v-select>
                                <span class="text-danger" v-if="errors.subcon_vendor_id">The Subcon vendor field is required</span>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Capacity</label> 
                                    <select class="form-control" v-model="vehicle_copied.capacity_id">
                                        <option v-for="(capacity,c) in capacities" v-bind:key="c" :value="capacity.id"> {{ capacity.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.capacity_id">The capacity field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Goods</label> 
                                    <select class="form-control" v-model="vehicle_copied.good_id">
                                        <option v-for="(good,g) in goods" v-bind:key="g" :value="good.id"> {{ good.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.good_id">The goods field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Allowed Total Weight</label> 
                                    <input type="text" id="allowed_total_weight" class="form-control" v-model="vehicle_copied.allowed_total_weight">
                                    <span class="text-danger" v-if="errors.allowed_total_weight">{{ errors.allowed_total_weight[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Based Trucks</label> 
                                    <select class="form-control" v-model="vehicle_copied.based_truck_id">
                                        <option v-for="(based_truck,b) in based_trucks" v-bind:key="b" :value="based_truck.id"> {{ based_truck.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.based_truck_id">The based truck field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Contract</label> 
                                    <select class="form-control" v-model="vehicle_copied.contract_id">
                                        <option v-for="(contract,c) in contracts" v-bind:key="c" :value="contract.id"> {{ contract.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.contract_id">The contract field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Remarks</label> 
                                    <input type="text" id="remarks" class="form-control" v-model="vehicle_copied.remarks">
                                    <span class="text-danger" v-if="errors.good_id">The remarks field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Document</label> 
                                    <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                    <span class="text-danger" v-if="errors.attachments">The attachment field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity Start Date</label> 
                                    <input type="date" id="validity_start_date" class="form-control" v-model="vehicle_copied.validity_start_date">
                                    <span class="text-danger" v-if="errors.validity_start_date">The validity start date field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity End Date</label> 
                                    <input type="date" id="validity_end_date" class="form-control" v-model="vehicle_copied.validity_end_date">
                                    <span class="text-danger" v-if="errors.validity_end_date">The validity end date field is required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="editVehicle(vehicle_copied)">Save</button>
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import vSelect from 'vue-select'
import Multiselect from 'vue-multiselect';
export default {
    components: {
        vSelect,
        Multiselect
    },
    data(){
        return {
            vehicles: [],
            vehicle: [],
            vehicle_copied: [],
            vehicle_id: '',
            categories: [],
            indicators: [],
            goods: [],
            based_trucks: [],
            contracts: [],
            documents: [],
            capacities:[],
            attachments: [],
            truckers: [],
            plants: [],
            selected: null,
            formData: new FormData(),
            fileSize: 0,
            errors: [],
            currentPage: 0,
            itemsPerPage: 10,
            keywords: '',
            show_plant: false,
            show_plant_add: false,
            vehicle_added: false,
            vehicle_updated: false
        }
    },
    created(){
        this.fetchVehicles();
        this.fetchCategories();
        this.fetchCapacities();
        this.fetchIndicators();
        this.fetchGoods();
        this.fetchBasedTrucks();
        this.fetchContracts();
        this.fetchDocuments();
        this.fetchTruckers();
        this.fetchPlants();
    },
    methods:{
        plantChange(){
            this.vehicle.indicator_id == 2 ? this.show_plant_add = false : this.show_plant_add = true;
            this.vehicle_copied.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
        },
        customLabelPlant (plant) {
            return `${plant.name }`
        },
        getVehicleId(id){
            this.errors = [];
            this.vehicle_id = id;
        },
        copyObject(vehicle){
            this.errors = [];
            this.vehicle_updated = false;
            this.vehicle_copied = Object.assign({}, vehicle)
            this.vehicle_copied.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
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
        fetchCategories(){
            axios.get('/categories')
            .then(response => { 
                this.categories = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchCapacities(){
             axios.get('/capacities')
            .then(response => { 
                this.capacities = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchIndicators(){
            axios.get('/indicators')
            .then(response => { 
                this.indicators = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchGoods(){
            axios.get('/goods')
            .then(response => { 
                this.goods = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchBasedTrucks(){
            axios.get('/based-trucks')
            .then(response => { 
                this.based_trucks = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchContracts(){
            axios.get('/contracts')
            .then(response => { 
                this.contracts = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchDocuments(){
            axios.get('/documents')
            .then(response => { 
                this.documents = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchTruckers(){
            axios.get('/truckers')
            .then(response => { 
                this.truckers = response.data
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        fetchPlants(){
            axios.get('/plants')
            .then(response => { 
                this.plants = response.data
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        prepareFields(){
            if(this.attachments.length > 0){
                for(var i = 0; i < this.attachments.length; i++){
                    let attachment = this.attachments[i];
                    this.formData.append('attachments[]', attachment);
                }
            } 
        },
        uploadFileChange(e){
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.attachments.push(files[i]);
                this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
            }
            if(this.fileSize > 5){
                alert('File size exceeds 5 MB');
                document.getElementById('attachments').value = "";
                this.attachments = [];
                this.fileSize = 0;
            }

        },
        resetData(){
            this.formData = new FormData();
            this.attachments = [];
            this.errors = [];
            this.vehicle = [];
            this.show_plant = false;
            document.getElementById('attachments').value = "";
            this.vehicle_added = false;
            
        },
        resetForm(){
            this.formData = new FormData();
            this.attachments = [];
            this.errors = [];
            this.vehicle = [];
            this.show_plant = false;
            document.getElementById('attachments').value = "";
        },
        addVehicle(vehicle){
            this.vehicle_added = false;
            document.getElementById('check_btn').disabled = true;
            var final_plant = [];
            vehicle.indicator_id == 2 ? final_plant = this.plants : final_plant = vehicle.plant;
            var plantIds = [];
            if(final_plant){
               final_plant.forEach((plant) => {
                    plantIds.push(plant.id);
                });
            }
            this.errors = [];
            this.prepareFields();
            this.formData.append('plate_number', vehicle.plate_number ? vehicle.plate_number.toUpperCase() : '');
            this.formData.append('category_id', vehicle.category_id ? vehicle.category_id : '');
            this.formData.append('capacity_id', vehicle.capacity_id ? vehicle.capacity_id : '');
            this.formData.append('vendor_id', vehicle.vendor ? vehicle.vendor.id : '');
            this.formData.append('subcon_vendor_id', vehicle.subcon_vendor ? vehicle.subcon_vendor.id : '');
            this.formData.append('indicator_id', vehicle.indicator_id ? vehicle.indicator_id : '');
            this.formData.append('good_id', vehicle.good_id ? vehicle.good_id : '');
            this.formData.append('allowed_total_weight', vehicle.allowed_total_weight ? vehicle.allowed_total_weight : '');
            this.formData.append('remarks', vehicle.remarks ? vehicle.remarks : '');
            this.formData.append('based_truck_id', vehicle.based_truck_id ? vehicle.based_truck_id : '');         
            this.formData.append('contract_id', vehicle.contract_id ? vehicle.contract_id : '');   
            this.formData.append('validity_start_date', vehicle.validity_start_date ? vehicle.validity_start_date : '');
            this.formData.append('validity_end_date', vehicle.validity_end_date ? vehicle.validity_end_date : '');
            this.formData.append('plants', plantIds ? plantIds : '');

            axios.post('/vehicle', this.formData)
            .then(response =>{
                this.vehicle_added = true;
                this.vehicles.unshift(response.data);
                this.resetForm();
                document.getElementById('check_btn').disabled = false;
            })
            .catch(error => {   
                this.errors = error.response.data.errors;
                document.getElementById('check_btn').disabled = false;
            })
        },
        editVehicle(vehicle){
            this.vehicle_updated = false;
            document.getElementById('edit_btn').disabled = true;
            var final_plant = [];
            vehicle.indicator_id == 2 ? final_plant = this.plants : final_plant = vehicle.plants;
            var plantIds = [];
            if(final_plant){
               final_plant.forEach((plant) => {
                    plantIds.push(plant.id);
                });
            }
            var index = this.vehicles.findIndex(item => item.id == vehicle.id);
            this.errors = [];
            this.prepareFields();
            this.formData.append('plate_number', vehicle.plate_number.toUpperCase());
            this.formData.append('category_id', vehicle.category_id);
            this.formData.append('capacity_id', vehicle.capacity_id);
            this.formData.append('vendor_id', vehicle.vendor ? vehicle.vendor.id : '');
            this.formData.append('subcon_vendor_id', vehicle.subcon_vendor ? vehicle.subcon_vendor.id : '');
            this.formData.append('indicator_id', vehicle.indicator_id);
            this.formData.append('good_id', vehicle.good_id);
            this.formData.append('allowed_total_weight', vehicle.allowed_total_weight);
            this.formData.append('remarks', vehicle.remarks);
            this.formData.append('based_truck_id', vehicle.based_truck_id);         
            this.formData.append('contract_id', vehicle.contract_id);   
            this.formData.append('validity_start_date', vehicle.validity_start_date);
            this.formData.append('validity_end_date', vehicle.validity_end_date);
            this.formData.append('plants', plantIds ? plantIds : '');
            this.formData.append('_method', 'PATCH');

            axios.post(`/vehicle/${vehicle.id}`, this.formData)
            .then(response => {
                this.vehicle_updated = true;
                this.vehicles.splice(index,1,response.data);
                document.getElementById('edit_btn').disabled = false;
            })
            .catch(error => {
                this.vehicle_updated = false;
                this.errors = error.response.data.errors;
                document.getElementById('edit_btn').disabled = false;
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
