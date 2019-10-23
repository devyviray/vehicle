<template>
    <div>
        <loader v-if="loading"></loader>
        <div class="header pt-md-8" style="background-color: #04703e">
            <div class="container-fluid">
                <div class="header-body">
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
                                <div class="col text-right" v-if="this.userLevel > 4">
                                    <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addVehicleModal" style="background-color: rgb(4, 112, 62);" @click="resetData()">Add Vehicle</a>
                                    <button :disabled="!downloadExcelbutton" class="btn btn-sm btn-primary" style="background-color: rgb(4, 112, 62);" @click="exportVehicle()">Download Excel</button>
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
                                        <th scope="col"></th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Plate number</th>
                                        <th scope="col">Plant Indicator</th>
                                        <th scope="col">Vendor</th>
                                        <th scope="col">Subcon vendor</th>
                                        <th scope="col">Capacity</th>
                                        <th scope="col">Goods</th>
                                        <th scope="col">Allowed total weight (KG)</th>
                                        <th scope="col">Based trucks</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">Contract</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Validity start date</th>
                                        <th scope="col">Validity end date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="table_loading">
                                        <td colspan="15">
                                            <content-placeholders>
                                                <content-placeholders-heading :img="true" />
                                                <content-placeholders-text :lines="3" />
                                            </content-placeholders>
                                        </td>
                                    </tr>

                                    <tr v-for="(vehicle, v) in filteredQueues" v-bind:key="v">
                                        <td class="text-right">
                                            <div class="dropdown" v-if="userLevel > 2">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" style="cursor: pointer" data-toggle="modal" data-target="#assignGPSModal" @click="viewAssignGPS(vehicle,vehicle.gpsdevice)" v-if="btn_assign">Assign GPS</a>
                                                    <a class="dropdown-item" style="cursor: pointer" @click="getVehicle(vehicle.id)" v-if="btn_edit">Edit</a>
                                                    <a class="dropdown-item" href="javascript.void(0)" data-toggle="modal" data-target="#viewDocumentsModal" @click="copyObject(vehicle)" v-if="btn_view">View Document</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td><i class="fas fa-location-arrow" title = "GPS Device: Yes" v-if="vehicle.gpsdevice"></i></td>
                                        <td>{{ vehicle.category.description }}</td>
                                        <td>{{ vehicle.plate_number }}</td>
                                        <td>{{ vehicle.indicator.description }}</td>
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
                                        <td>{{ vehicle.user.name }}</td>
                                        <td>{{ vehicle.validity_start_date }}</td>
                                        <td>{{ vehicle.validity_end_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3 mt-3 ml-1" v-if="filteredQueues.length ">
                            <div class="col-6">
                                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ currentPage + 1 }} of {{ totalPages }}</span>
                                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(currentPage + 1)"> Next </button>
                            </div>
                            <div class="col-6 text-right">
                                <span>{{ filteredQueues.length }} Filtered Vehicle(s)</span><br>
                                <span>{{ vehicles.length }} Total Vehicle(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Vehicle Modal -->
        <div class="modal fade" id="addVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                                    <label for="role">Category*</label> 
                                    <select class="form-control" v-model="vehicle.category_id">
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category_id">The category field is required.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plate Number*</label> 
                                    <input type="text" id="plate_number" class="form-control" v-model="vehicle.plate_number" style="text-transform:uppercase">
                                    <span class="text-danger" v-if="errors.plate_number">{{ errors.plate_number[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plant Indicator*</label> 
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
                                <label for="role">Vendor*</label> 
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
                                    <label for="role">Capacity*</label>
                                    <select class="form-control" v-model="vehicle.capacity_id">
                                        <option v-for="(capacity,c) in capacities" v-bind:key="c" :value="capacity.id"> {{ capacity.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.capacity_id">The capacity field is required.</span>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Goods</label> 
                                    <select class="form-control" v-model="vehicle.good_id">
                                        <option></option>
                                        <option v-for="(good,g) in goods" v-bind:key="g" :value="good.id"> {{ good.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.good_id">The goods field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Allowed Total Weight (KG)</label> 
                                    <input type="text" id="allowed_total_weight" class="form-control" v-model="vehicle.allowed_total_weight" @keypress="onlyNumber" maxlength="20">
                                    <span class="text-danger" v-if="errors.allowed_total_weight">{{ errors.allowed_total_weight[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Based Trucks*</label> 
                                    <select class="form-control" v-model="vehicle.based_truck_id" id="based_truck">
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
                                    <select class="form-control" v-model="vehicle.contract_id" id="contract">
                                        <option></option>
                                        <option v-for="(contract,c) in contracts" v-bind:key="c" :value="contract.id"> {{ contract.code + ' - ' + contract.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.contract_id">The contract field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Remarks</label> 
                                    <input type="text" id="remarks" class="form-control" v-model="vehicle.remarks" maxlength="40">
                                    <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Document*</label>
                                    <input type="file" multiple="multiple" id="attachments" placeholder="Attach file" @change="uploadFileChange"><br>
                                    <span class="text-danger" v-if="errors.attachments">The attachment is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity Start Date*</label> 
                                    <input type="date" id="validity_start_date" class="form-control" v-model="vehicle.validity_start_date">
                                    <span class="text-danger" v-if="errors.validity_start_date">The validity start date is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity End Date*</label> 
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

        <div class="modal fade" id="editVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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
                                    <label for="role">Category*</label> 
                                    <select class="form-control" v-model="vehicle_fetch.category_id" disabled>
                                        <option v-for="(category,c) in categories" v-bind:key="c" :value="category.id"> {{ category.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.category_id">The category field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plate Number*</label> 
                                    <input type="text" id="plate_number" class="form-control" v-model="vehicle_fetch.plate_number" disabled>
                                    <span class="text-danger" v-if="errors.plate_number">{{ errors.plate_number[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Plant Indicator*</label> 
                                    <select class="form-control" v-model="vehicle_fetch.indicator_id" @change="plantChange">
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
                                        v-model="vehicle_fetch.plants"
                                        :options="plants"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabelPlant"
                                        placeholder="Select Plant"
                                        id="selected_plant"
                                    >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.plants">{{ errors.plants[0] }}</span>
                                </div>  
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-4">
                                <label for="role">Vendor*</label> 
                                <v-select
                                    style="width: 100%" 
                                    v-model="vehicle_fetch.vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                    disabled
                                >      
                                </v-select>
                                <span class="text-danger" v-if="errors.vendor_id">The contract field is required</span>
                            </div>
                            <div class="col-md-4">
                                <label for="role">Subcon Vendor</label> 
                                <v-select 
                                    style="width: 100%"
                                    v-model="vehicle_fetch.subcon_vendor"
                                    label="vendor_description_lfug"
                                    :options="truckers"
                                    track-by="id"
                                    disabled
                                >
                                </v-select>
                                <span class="text-danger" v-if="errors.subcon_vendor_id">The Subcon vendor field is required</span>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Capacity*</label> 
                                    <select class="form-control" v-model="vehicle_fetch.capacity_id" id="capacity-edit">
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
                                    <select class="form-control" v-model="vehicle_fetch.good_id" id="good-edit">
                                        <option></option>
                                        <option v-for="(good,g) in goods" v-bind:key="g" :value="good.id"> {{ good.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.good_id">The goods field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Allowed Total Weight (KG)</label> 
                                    <input type="text" id="allowed_total_weight-edit" class="form-control" v-model="vehicle_fetch.allowed_total_weight" @keypress="onlyNumber" maxlength="20">
                                    <span class="text-danger" v-if="errors.allowed_total_weight">{{ errors.allowed_total_weight[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Based Trucks*</label> 
                                    <select class="form-control" v-model="vehicle_fetch.based_truck_id" id="based_truck-edit">
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
                                    <select class="form-control" v-model="vehicle_fetch.contract_id"  id="contract-edit">
                                        <option></option>
                                        <option v-for="(contract,c) in contracts" v-bind:key="c" :value="contract.id"> {{ contract.description }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.contract_id">The contract field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Remarks</label> 
                                    <input type="text" id="remarks-edit" class="form-control" v-model="vehicle_fetch.remarks" maxlength="40">
                                    <span class="text-danger" v-if="errors.good_id">The remarks field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Document</label> 
                                    <input type="file" multiple="multiple" id="attachments" class="attachments-edit" placeholder="Attach file" @change="uploadFileChange"><br>
                                    <span class="text-danger" v-if="errors.attachments">The attachment field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity Start Date*</label> 
                                    <input type="date" id="validity_start_date-edit" class="form-control" v-model="vehicle_fetch.validity_start_date">
                                    <span class="text-danger" v-if="errors.validity_start_date">The validity start date field is required</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="role">Validity End Date*</label> 
                                    <input type="date" id="validity_end_date-edit" class="form-control" v-model="vehicle_fetch.validity_end_date">
                                    <span class="text-danger" v-if="errors.validity_end_date">{{ errors.validity_end_date[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="editVehicle(vehicle_fetch)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Vehicle Modal -->
        <div class="modal fade" id="deleteVehicleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
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

        <!-- View Documents Modal -->
        <div class="modal fade" id="viewDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">File name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, d) in this.vehicle_copied.documents" v-bind:key="d">
                                <td>{{ d + 1 }}</td>
                                <td>{{ document.file_name }}</td>
                                <td><span style="text-decoration: none; color: #5e72e4; background-color: transparent; cursor: pointer;" @click="downloadAttachment(document.id)">Download Document</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>

        <!-- Assign GPS Modal -->
        <div class="modal fade" id="assignGPSModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <!-- <span class="closed" data-dismiss="modal">&times;</span> -->
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyLabel">ASSIGN GPS DEVICE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="alert alert-success" v-if="assigned_gps">
                            <strong>Success!</strong> GPS device succesfully assigned
                        </div>
                        <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">PLATE NUMBER</label> 
                                    <input type="text" id="plate_number" class="form-control" v-model="gps_device.plate_number" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">IMEI NUMBER</label> 
                                    <input type="text" id="imei" class="form-control" v-model="gps_device.imei" @keypress="gpsNumber" maxlength="15" placeholder="XXXXXXXXXXXXXXX" required>
                                    <span class="text-danger" v-if="errors.imei">{{ errors.imei[0] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">SIM NUMBER</label> 
                                    <input type="text" id="sim_number" class="form-control" v-model="gps_device.sim_number" @keypress="gpsNumber" maxlength="11" placeholder="09XXXXXXXXX" required>
                                    <span class="text-danger" v-if="errors.sim_number">{{ errors.sim_number[0] }}</span>
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Attachment(s)</label> 
                                    <input type="file" multiple="multiple" id="gps_attachments" class="gps-attachments-edit" placeholder="Attach file" @change="uploadGPSFileChange" v-if="isUploadGPSAttachment"><br>
                                    <span class="text-danger" v-if="errors.gps_attachments">The attachment field is required</span>
                                </div>
                            </div>

                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">File name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(attachment, d) in this.vehicle_copied.gpsdeviceattachments" v-bind:key="d">
                                        <td>{{ d + 1 }}</td>
                                        <td>{{ attachment.file_name }}</td>
                                        <td>
                                            <span style="text-decoration: none; color: #5e72e4; background-color: transparent; cursor: pointer;"  title="Download" @click="downloadGPSAttachment(attachment.id)">Download</span>
                                            <span> | </span>
                                            <span style="text-decoration: none; color: red; background-color: transparent; cursor: pointer;" title="Delete" @click="deleteGPSAttachment(attachment.id)">Delete</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="assign_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="assignGPS(gps_device)">Assign</button>
                        <button id="assign_btn" type="button" data-toggle="modal" data-target="#reassignGPSModal" class="btn btn-warning btn-round btn-fill" @click="viewreassignGPS(gps_device)" v-if="gps_device_id">Re-Assign</button>
                        <button id="assign_btn" type="button" data-toggle="modal" data-target="#deleteGPSModal" class="btn btn-danger btn-round btn-fill" v-if="gps_device_id">Remove</button>
                    </div>
                </div>
            </div>  
        </div>

        <!-- Reassign GPS Modal   -->

        <div class="modal fade" id="reassignGPSModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <!-- <span class="closed" data-dismiss="modal">&times;</span> -->
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">RE-ASSIGN GPS DEVICE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                
                                <label for="role">Select Vehicle to Re-assign GPS Device</label> 
                                <multiselect
                                    v-model="reassign_vehicle"
                                    :options="reassign_vehicles"
                                    :multiple="false"
                                    track-by="id"
                                    :custom-label="customLabelReassignVehicle"
                                    placeholder="Select Vehicle"
                                    id="selected_reassign_vehicle"
                                >
                                </multiselect>
                                <span class="text-danger" v-if="errors.reassign_vehicles">{{ errors.reassign_vehicles[0] }}</span>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" @click="reassignGPS">Re-Assign</button>
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                  
                </div>
                </div>
            </div>
        </div>

         <!-- Delete GPS Device Modal -->
        <div class="modal fade" id="deleteGPSModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Remove GPS Device</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to remove this GPS Device?
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss='modal'>Close</button>
                    <button class="btn btn-warning" @click="deleteGPSDevice">Remove</button>
                </div>
                </div>
            </div>
        </div>


    </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<script>
import vSelect from 'vue-select'
import Multiselect from 'vue-multiselect'
import loader from './Loader'
import VueContentPlaceholders from 'vue-content-placeholders'
import XLSX from 'xlsx'

export default {
    props:['userLevel','role'],
    components: {
        vSelect,
        Multiselect,
        loader,
        VueContentPlaceholders
    },
    data(){
        return {
            vehicles: [],
            vehicle: [],
            vehicle_fetch: [],
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
            itemsPerPage: 50,
            keywords: '',
            show_plant: false,
            show_plant_add: false,
            reassign_vehicle : '',
            reassign_vehicles : [],
            vehicle_added: false,
            vehicle_updated: false,
            loading: false,
            table_loading: false,
            old_plants: [],
            downloadExcelbutton:false,
            formGPSData: new FormData(),
            assigned_gps:false,
            gps_device: [],
            gps_device_id: '',
            gps_device_attachments: [],
            gps_device_files: [],
            isUploadGPSAttachment : true,
            btn_assign: false,
            btn_edit: false,
            btn_view: false,

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
        this.buttonAuth();
    },
    methods:{
        exportVehicle(){
            var vehicleData = [];
            for (var i = 0; i < this.vehicles.length; i++) {
                var has_gps = "";
                var imei = "";
                var sim_number = "";
                var subcon_vendor = "";
                var goods = "";
                var allowed_total_weight = "";
                var contract = "";

                if(this.vehicles[i].gpsdevice){
                    has_gps = "Yes";
                    imei = this.vehicles[i].gpsdevice.imei ? this.vehicles[i].gpsdevice.imei : "";
                    sim_number = this.vehicles[i].gpsdevice.sim_number ? this.vehicles[i].gpsdevice.sim_number : "";
                }else{
                    has_gps = "No";
                    imei = "";
                    sim_number = "";
                }
               
                if(this.vehicles[i].subcon_vendor){
                    subcon_vendor = this.vehicles[i].subcon_vendor.vendor_description_lfug;
                }
                if(this.vehicles[i].good){
                    goods = this.vehicles[i].good.description;
                }
                if(this.vehicles[i].allowed_total_weight){
                    allowed_total_weight = this.vehicles[i].allowed_total_weight;
                }
                if(this.vehicles[i].contract){
                    contract = this.vehicles[i].contract.code;
                }

                vehicleData.push({
                    "GPS": has_gps,
                    "IMEI": imei,
                    "SIM NUMBER": sim_number,
                    "CATEGORY": this.vehicles[i].category.description,
                    "PLATE NUMBER": this.vehicles[i].plate_number,
                    "PLANT INDICATOR": this.vehicles[i].indicator.description,
                    "VENDOR":this.vehicles[i].vendor.vendor_description_lfug,
                    "SUBCON VENDOR": subcon_vendor,
                    "CAPACITY": this.vehicles[i].capacity.description,
                    "GOODS":goods,
                    "ALLOWED TOTAL WEIGHT (KG)": allowed_total_weight,
                    "BASED TRUCKS":this.vehicles[i].based_truck.description,
                    "REMARKS": this.vehicles[i].remarks,
                    "VALIDITY START DATE": this.vehicles[i].validity_start_date,
                    "VALIDITY END DATE": this.vehicles[i].validity_end_date,
                });
            }

            var exportedData  = XLSX.utils.json_to_sheet(vehicleData)
            var wb = XLSX.utils.book_new()
            XLSX.utils.book_append_sheet(wb, exportedData,'Vehicle List') 
            XLSX.writeFile(wb, 'Vechicle List.xlsx')
        },
        disabledEdit(){
            document.getElementById('capacity-edit').disabled = true;
            document.getElementById('good-edit').disabled = true;
            document.getElementById('based_truck-edit').disabled = true;
            document.getElementById('contract-edit').disabled = true;
            document.getElementById('validity_start_date-edit').disabled = true;
            document.getElementById('validity_end_date-edit').disabled = true;
            document.getElementById('allowed_total_weight-edit').readOnly = true;
            document.getElementById('remarks-edit').readOnly = true;
            $('.attachments-edit').attr('disabled','disabled');
        },
        getVehicle(id){
            axios.get(`/vehicle-specific/${id}`)
            .then(response => {
                this.vehicle_fetch = response.data;
                this.old_plants = response.data.plants;
                $('#editVehicleModal').modal('show');
                this.vehicle_copied.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
                this.vehicle_fetch.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
                if(this.userLevel < 5){
                    this.disabledEdit();
                }
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        downloadAttachment(id){
            var base_url = window.location.origin;
            window.location = base_url+`/download-attachment/${id}`;
        },
        downloadGPSAttachment(id){
            var base_url = window.location.origin;
            window.location = base_url+`/download-gps-attachment/${id}`;
        },
        onlyNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
                $event.preventDefault();
            }
        },
        gpsNumber ($event) {
            let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
            if ((keyCode < 48 || keyCode > 57)) { // 46 is dot
                $event.preventDefault();
            }
        },
        plantChange(){
            this.vehicle.indicator_id == 2 ? this.show_plant_add = false : this.show_plant_add = true;
            this.vehicle_copied.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
            this.vehicle_fetch.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
        },
        customLabelPlant (plant) {
            return `${plant.name }`
        },
        customLabelReassignVehicle (reassign) {
            return `${reassign.plate_number }`
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
            this.table_loading = true;
            axios.get('/vehicle')
            .then(response => { 
                this.vehicles = response.data;
                this.table_loading = false;
                this.downloadExcelbutton = true;
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
            this.loading = true;
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
                this.loading = false;
            })
            .catch(error => {   
                this.errors = error.response.data.errors;
                this.attachments = [];
                document.getElementById('check_btn').disabled = false;
                this.loading = false;
            })
        },
        editVehicle(vehicle){
            this.vehicle_updated = false;
            this.loading = true;
            document.getElementById('edit_btn').disabled = true;
            var final_plant = [];
            vehicle.indicator_id == 2 ? final_plant = this.plants : final_plant = vehicle.plants;
            var plantIds = [];
            if(final_plant){
               final_plant.forEach((plant) => {
                    plantIds.push(plant.id);
                });
            }
            var oldPlants = [];
            this.old_plants.forEach((fetch) => {
                oldPlants.push(fetch.id);
            });
           
            var index = this.vehicles.findIndex(item => item.id == vehicle.id);
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
            this.formData.append('old_plants', oldPlants ? oldPlants : '');
            this.formData.append('_method', 'PATCH');

            axios.post(`/vehicle/${vehicle.id}`, this.formData)
            .then(response => {
                this.vehicle_updated = true;
                this.vehicles.splice(index,1,response.data);
                document.getElementById('edit_btn').disabled = false;
                this.loading = false;
            })
            .catch(error => {
                this.vehicle_updated = false;
                this.errors = error.response.data.errors;
                this.attachments = [];
                document.getElementById('edit_btn').disabled = false;
                this.loading = false;
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
        fetchReassignVehicle(){
            let v = this;
            v.reassign_vehicles = [];
            this.vehicles.forEach(e => {
                if(e.gpsdevice == null){
                    v.reassign_vehicles.push(e);
                }
            });
        },
        viewAssignGPS(vehicle,gps_device){
            this.formGPSData = new FormData();
            this.loading = false;
            this.assigned_gps = false;
            this.resetAssignGPS();
            this.vehicle_copied = Object.assign({}, vehicle);
            this.gpsDeviceAttachmentButton();
            if(gps_device){
                this.gps_device_id = gps_device.id;
                this.gps_device.imei = gps_device.imei;
                this.gps_device.sim_number = gps_device.sim_number;
            }else{
                this.gps_device_id = null;
                this.gps_device.imei = null;
                this.gps_device.sim_number = null;
                this.gps_device_attachments = [];
            }

            this.gps_device.plate_number = this.vehicle_copied.plate_number; 
        },
        buttonAuth(){
            console.log(this.role); 
            if(this.role == "GPS Custodian"){
                this.btn_assign = true;
                this.btn_edit = false;
                this.btn_view = false;
            }else{
                this.btn_assign = false;
                this.btn_edit = true;
                this.btn_view = true;

            }
        },
        gpsDeviceAttachmentButton(){
            if(this.vehicle_copied.gpsdeviceattachments.length < 5){
                this.isUploadGPSAttachment = true;
            }else{
                this.isUploadGPSAttachment = false;
            }
        },
        prepareGPSAttachmentFields(){
            if(this.gps_device_attachments.length > 0){
                for(var i = 0; i < this.gps_device_attachments.length; i++){
                    let gps_device_attachments = this.gps_device_attachments[i];
                    this.formGPSData.append('attachments[]', gps_device_attachments);
                }
            } 
        },
        uploadGPSFileChange(e){
            var files = e.target.files || e.dataTransfer.files;

            if(!files.length)
                return;
            
            for (var i = files.length - 1; i >= 0; i--){
                this.gps_device_attachments.push(files[i]);
                this.fileSize = this.fileSize+files[i].size / 1024 / 1024;
            }
            if(this.fileSize > 5){
                alert('File size exceeds 5 MB');
                document.getElementById('gps_attachments').value = "";
                this.gps_device_attachments = [];
                this.fileSize = 0;
            }

        },
        viewreassignGPS(){
           this.reassign_vehicle = '';
           this.fetchReassignVehicle();
        },
        reassignGPS(){
            let v = this;
            this.loading = true;
            this.formGPSData = new FormData();
            this.errors = [];

            if(this.gps_device_id){
                
                this.formGPSData.append('vehicle_id', this.vehicle_copied.id);    
                this.formGPSData.append('reassign_vehicle_id', this.reassign_vehicle.id);
                this.formGPSData.append('gps_device_id', this.gps_device_id);
                this.formGPSData.append('plate_number', this.reassign_vehicle.plate_number);
                this.formGPSData.append('_method', 'PATCH');

                 axios.post(`/reassign_gps_device/${this.gps_device_id}`, this.formGPSData)
                .then(response =>{
                    var orginal_vehicle_index = this.vehicles.findIndex(item => item.id == this.vehicle_copied.id);
                    var reassigned_vehicle_index = this.vehicles.findIndex(item => item.id == this.reassign_vehicle.id);
                    response.data.forEach((data) => {
                        if(v.vehicle_copied.id == data.id){
                            v.vehicles.splice(orginal_vehicle_index,1,data);
                        }
                        if(this.reassign_vehicle.id == data.id){
                            this.vehicles.splice(reassigned_vehicle_index,1,data);
                        }
                    });
                    this.loading = false;
                    $('#reassignGPSModal').modal('hide');
                    $('#assignGPSModal').modal('hide');
                    alert('GPS Device successfully reassigned');
                    
                    
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                })
            }
        },
        assignGPS(gps_device){
            this.formGPSData = new FormData();
            var index = this.vehicles.findIndex(item => item.id == this.vehicle_copied.id);
            this.loading = true;
            this.assigned_gps = false;
            this.errors = [];

          
            if(this.gps_device_id){

                this.prepareGPSAttachmentFields();
                this.formGPSData.append('vehicle_id', this.vehicle_copied.id);
                this.formGPSData.append('imei', gps_device.imei);
                this.formGPSData.append('sim_number', gps_device.sim_number);
                this.formGPSData.append('_method', 'PATCH');

                axios.post(`/gps_device/${this.gps_device_id}`, this.formGPSData)
                .then(response =>{
                    document.getElementById('gps_attachments').value = "";
                    this.gps_device_attachments = [];
                    this.loading = false;
                    this.assigned_gps = true;
                    this.vehicles.splice(index,1,response.data);
                    this.vehicle_copied = Object.assign({}, response.data);
                    this.gpsDeviceAttachmentButton();
                })
                .catch(error => {
                    this.loading = false;
                    this.assigned_gps = false;
                    this.errors = error.response.data.errors;
                })

            }else{

                this.prepareGPSAttachmentFields();
                this.formGPSData.append('vehicle_id', this.vehicle_copied.id);
                this.formGPSData.append('imei', gps_device.imei);
                this.formGPSData.append('sim_number', gps_device.sim_number);
                this.formGPSData.append('_method', 'POST');

                axios.post('/gps_device', this.formGPSData)    
                .then(response =>{
                    document.getElementById('gps_attachments').value = "";
                    this.gps_device_attachments = [];
                    this.loading = false;
                    this.assigned_gps = true;
                    this.gps_device_id = response.data.gps_device_id;
                    this.vehicles.splice(index,1,response.data);
                    this.vehicle_copied = Object.assign({}, response.data);
                    this.gpsDeviceAttachmentButton();
                })
                .catch(error => {
                    this.loading = false;
                    this.assigned_gps = false;
                    this.errors = error.response.data.errors;
                })
            }
        },
        deleteGPSDevice(){
            this.loading = true;
            var index = this.vehicles.findIndex(item => item.id == this.vehicle_copied.id);
            axios.delete(`/gps_device/${this.gps_device_id}`)
            .then(response => {
                $('#deleteGPSModal').modal('hide');
                $('#assignGPSModal').modal('hide');
                this.vehicles.splice(index,1,response.data);
                this.loading = false;
                 alert('GPS Device successfully removed');
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        deleteGPSAttachment($id){
            var index = this.vehicles.findIndex(item => item.id == this.vehicle_copied.id);
            if(confirm("Do you really want to delete this GPS Device Attachment?")){
                axios.delete(`/delete-gps-attachment/${$id}`)
                .then(response => {
                     this.vehicles.splice(index,1,response.data);
                     this.vehicle_copied = Object.assign({}, response.data);
                     this.gpsDeviceAttachmentButton();
                     alert('GPS Device Attachment successfully deleted');
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                })
            }
        },
        resetAssignGPS(){
            this.errors = [];
            this.gps_device = [];
            this.gps_device_attachments = [];
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
