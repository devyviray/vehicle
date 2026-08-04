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
                                    <h3 class="mb-0">User List</h3>
                                </div> 
                                <div class="col text-right">
                                    <a href="javascript.void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal" style="background-color: rgb(4, 112, 62);" @click="resetForm()">Add User</a>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-xl-4 mb-2 mt-3 float-right">
                                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="keywords" name="users-search-filter" autocomplete="off" autocapitalize="off" spellcheck="false" @input="onSearchInput($event)">
                                </div>
                                <div class="col-xl-3 mb-2 mt-3 float-right">
                                    <select class="form-control" v-model="filterRole" @change="onRoleFilterChange">
                                        <option value="">All Roles</option>
                                        <option v-for="(role, r) in roles" :key="'filter-role-' + r" :value="role.id">
                                            {{ role.name }}
                                        </option>
                                    </select>
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
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(user)">Edit</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(user)">Delete</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#changePasswordModal" style="cursor: pointer" @click="copyObject(user)">Change Password</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td scope="row">{{ user.id }}</td>
                                        <td>{{ user.name ? user.name : 'n/a' }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.roles[0].name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3 mt-3 ml-1" v-if="pagination.total > 0">
                            <div class="col-6">
                                <button :disabled="!showPreviousLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(pagination.current_page - 1)"> Previous </button>
                                    <span class="text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
                                <button :disabled="!showNextLink()" class="btn btn-default btn-sm btn-fill" v-on:click="setPage(pagination.current_page + 1)"> Next </button>
                            </div>
                            <div class="col-6 text-right">
                                <span>Showing {{ pagination.from || 0 }} - {{ pagination.to || 0 }} of {{ pagination.total }} User(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add User Modal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Add User</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="user_added">
                            <strong>Success!</strong> User succesfully added
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text" class="form-control" v-model="user.name" style="text-transform:uppercase">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Email*</label> 
                                    <input type="text" class="form-control" v-model="user.email">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Default Password*</label> 
                                    <input type="password" class="form-control" v-model="user.default_password">
                                    <span class="text-danger" v-if="errors.password">{{ errors.password[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="user.role" @change="changeRole(user.role)">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">The indicator field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_based_trucks">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Based Trucks</label>
                                    <multiselect
                                            v-model="user.based_trucks"
                                            :options="based_trucks"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabel"
                                            placeholder="Select based truck"
                                        >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.based_trucks">The based trucks field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_plants">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="role">Plant Indicator*</label>
                                    <select class="form-control" v-model="user.indicator_id" @change="plantChange">
                                        <option v-for="(indicator, i) in indicators" v-bind:key="i" :value="indicator.id">
                                            {{ indicator.description }}
                                        </option>
                                    </select>
                                    <span class="text-danger" v-if="errors.indicator_id">{{ errors.indicator_id[0] }}</span>
                                </div>
                            </div>
                            <div v-if="show_plant_add" class="col-lg-12">
                                <div class="form-group">
                                    <label for="role">Plant</label>
                                    <!-- add a select all option in multiselect -->
                                    <multiselect v-model="user.plant" :options="plants" :multiple="true" track-by="id"
                                        :custom-label="customLabelPlant" placeholder="Select Plant" id="selected_plant">
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.plants">{{ errors.plants[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="add_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="addUser(user)">Save</button>
                    </div>
                    </div>
                </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Edit User</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="user_updated">
                            <strong>Success!</strong> User succesfully updated
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Name*</label> 
                                    <input type="text"  class="form-control" v-model="user_copied.name" style="text-transform:uppercase">
                                    <span class="text-danger" v-if="errors.name">{{ errors.name[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Email*</label> 
                                    <input type="text" class="form-control" v-model="user_copied.email">
                                    <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="copied_role" @change="changeRole(copied_role)">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_based_trucks">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Based Trucks</label>
                                    <multiselect
                                            v-model="user_copied.based_trucks"
                                            :options="based_trucks"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabel"
                                            placeholder="Select based truck"
                                        >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.based_trucks">The based trucks field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="show_plants">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="role">Plant Indicator*</label>
                                    <select class="form-control" v-model="user_copied.indicator_id" @change="plantChange">
                                        <option v-for="(indicator, i) in indicators" v-bind:key="i" :value="indicator.id">
                                            {{ indicator.description }}
                                        </option>
                                    </select>
                                    <span class="text-danger" v-if="errors.indicator_id">{{ errors.indicator_id[0] }}</span>
                                </div>
                            </div>
                            <div v-if="show_plant_edit" class="col-lg-12">
                                <div class="form-group">
                                    <label for="role">Plant</label>
                                    <!-- add a select all option in multiselect -->
                                    <multiselect v-model="user_copied.plants" :options="plants" :multiple="true" track-by="id"
                                        :custom-label="customLabelPlant" placeholder="Select Plant" id="selected_plant">
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.plants">{{ errors.plants[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="updateUser(user_copied, copied_role)">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCompanyLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                Are you sure you want to delete this User?
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

        
        <!-- Change password Modal -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <span class="closed" data-dismiss="modal">&times;</span>
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-header">
                        <h2 class="col-12 modal-title text-center" id="addCompanyLabel">Change Password</h2>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" v-if="user_updated">
                            <strong>Success!</strong> Password succesfully changed
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">New password*</label> 
                                    <input type="password"  class="form-control" v-model="user.new_password">
                                    <span class="text-danger" v-if="errors.new_password">{{ errors.new_password[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Confirm password*</label> 
                                    <input type="password" class="form-control" v-model="user.new_password_confirmation">
                                    <span class="text-danger" v-if="errors.new_password_confirmation">{{ errors.new_password_confirmation[0] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit_btn" type="button" class="btn btn-primary btn-round btn-fill" @click="changePassword(user)" >Save</button>
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
import loader from '../Loader'
export default {
    props:['userLevel'],
    components: {
        vSelect,
        Multiselect,
        loader
    },
    data(){
        return {
            users: [],
            user: [],
            user_copied: [],
            copied_role: [],
            roles: [],
            based_trucks: [],
            plants: [],
            indicators: [],
            errors: [],
            currentPage: 1,
            itemsPerPage: 10,
            keywords: '',
            filterRole: '',
            loading: false,
            searchDebounceTimer: null,
            isBootstrappingSearch: true,
            pagination: {
                current_page: 1,
                last_page: 1,
                per_page: 10,
                total: 0,
                from: 0,
                to: 0
            },
            user_added: false,
            user_updated: false,
            user_id: '',
            show_based_trucks: false,
            show_plants: false,
            show_plant_add: false,
            show_plant_edit: false
        }
    },
    created(){
        this.fetchRoles();
        this.fetchBasedTrucks();
        this.fetchPlants();
        this.fetchIndicators();
    },
    mounted() {
        this.sanitizeSearchField();
        this.fetchUsers(1);
        this.$nextTick(() => {
            setTimeout(() => {
                this.sanitizeSearchField();
            }, 300);
            setTimeout(() => {
                this.isBootstrappingSearch = false;
            }, 800);
        });
        window.addEventListener('pageshow', this.handlePageShow);
    },
    methods:{
        handlePageShow() {
            this.sanitizeSearchField();
            this.fetchUsers(1);
        },
        sanitizeSearchField() {
            this.keywords = '';
            const searchInput = this.$el.querySelector('#keywords');
            if (searchInput) {
                searchInput.value = '';
            }
        },
        buildUserParams(page = 1) {
            let params = {
                page: page,
                per_page: this.itemsPerPage,
                keywords: this.keywords ? this.keywords.trim() : ''
            };

            if (this.filterRole) {
                params.role_id = this.filterRole;
            }

            return params;
        },
        onSearchInput(event) {
            if (this.isBootstrappingSearch && event && document.activeElement !== event.target) {
                this.sanitizeSearchField();
                return;
            }

            clearTimeout(this.searchDebounceTimer);
            this.searchDebounceTimer = setTimeout(() => {
                this.fetchUsers(1);
            }, 400);
        },
        onRoleFilterChange() {
            this.fetchUsers(1);
        },
        changeRole(role){
            role > 3 ? this.show_based_trucks = true : this.show_based_trucks = false;
            role == 10 ? this.show_plants = true : this.show_plants = false;
        },
        changePassword(user){
            axios.post('/change-password', {
                user_id: this.user_id,
                new_password: user.new_password,
                new_password_confirmation: user.new_password_confirmation
            })
            .then(response => {
                $('#changePasswordModal').modal('hide');
                alert('Password successfully changed');
                this.resetForm();
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        customLabel (based_truck) {
            return `${based_truck.description  }`
        },
        copyObject(user){
            this.resetForm();
            this.user_copied = Object.assign({}, user);
            this.copied_role = this.user_copied.roles[0].id;
            this.user_id = user.id;
            user.roles[0].level < 4 ? this.show_based_trucks = true : this.show_based_trucks = false;
            user.roles[0].id == 10 ? this.show_plants = true : this.show_plants = false;
            user.indicator_id == 2 ? this.show_plant_edit = false : this.show_plant_edit = true;
            this.user_updated = false;
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
        fetchPlants(){
            axios.get('/plants')
            .then(response => { 
                this.plants = response.data;
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
        customLabelPlant(plant) {
            return `${plant.code} ${plant.name} - ${plant.company_server}`
        },
        plantChange() {
            this.user.indicator_id == 2 ? this.show_plant_add = false : this.show_plant_add = true;
            this.user_copied.indicator_id == 2 ? this.show_plant = false : this.show_plant = true;
            this.user_copied.indicator_id == 2 ? this.show_plant_edit = false : this.show_plant_edit = true;
        },
        fetchRoles(){
            axios.get('/roles')
            .then(response => { 
                this.roles = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchUsers(page = 1){
            this.loading = true;
            axios.get('/users-table', { params: this.buildUserParams(page) })
            .then(response => { 
                this.users = response.data.data;
                this.currentPage = response.data.current_page;
                this.pagination.current_page = response.data.current_page;
                this.pagination.last_page = response.data.last_page;
                this.pagination.per_page = response.data.per_page;
                this.pagination.total = response.data.total;
                this.pagination.from = response.data.from;
                this.pagination.to = response.data.to;
                this.loading = false;
            })
            .catch(error => { 
                this.errors = error.response && error.response.data ? error.response.data.error : [];
                this.loading = false;
            })
        },
        resetForm(){
            this.errors = [];
            this.user = [];
            this.show_based_trucks = false;
            this.show_plants = false;
            this.show_plant_add = false;
        },
        addUser(user){
            var based_trucks_ids = [];
            if(user.based_trucks){
                user.based_trucks.forEach((based_truck) => {
                    based_trucks_ids.push(based_truck.id);
                });
            }
            var plant_ids = [];
            if(user.plant){
                user.plant.forEach((plant) => {
                    plant_ids.push(plant.id);
                });
            }
        
            this.user_added = false;
            this.loading = true;
            document.getElementById('add_btn').disabled = true;
            if (user.role != 10) {
                user.indicator_id = '';
                plant_ids = [];
            }
            axios.post('/user', {
                name: user.name,
                email: user.email,
                password: user.default_password,
                role: user.role,
                based_trucks: based_trucks_ids,
                indicator_id: user.indicator_id,
                plants: plant_ids
            })
            .then(response =>{
                this.user_added = true;
                this.fetchUsers(1);
                this.resetForm();
                document.getElementById('add_btn').disabled = false;
                this.loading = false;
            })
            .catch(error => {   
                this.errors = error.response.data.errors;
                document.getElementById('add_btn').disabled = false;
                this.loading = false;
            })
        },
        updateUser(user_copied, copied_role){
            this.errors = [];
            var based_trucks_ids = [];
            user_copied.based_trucks.forEach((based_truck) => {
                based_trucks_ids.push(based_truck.id);
            });
            var plant_ids = [];
            if(user_copied.plants){
                user_copied.plants.forEach((plant) => {
                    plant_ids.push(plant.id);
                });
            }

            this.edit_updated = false;
            this.loading = true;
            document.getElementById('edit_btn').disabled = true;
            if (copied_role != 10) {
                user_copied.indicator_id = '';
                plant_ids = [];
            }
            axios.post(`/user/${user_copied.id}`, {
                name: user_copied.name,
                email: user_copied.email,
                password: user_copied.default_password,
                role: copied_role,
                based_trucks: based_trucks_ids,
                indicator_id: user_copied.indicator_id,
                plants: plant_ids,
                _method: 'PATCH'
            })
            .then(response => {
                this.user_updated = true;
                this.fetchUsers(this.pagination.current_page || 1);
                document.getElementById('edit_btn').disabled = false;
                this.loading = false;
            })
            .catch(error => {
                this.user_updated = false;
                this.errors = error.response.data.errors;
                document.getElementById('edit_btn').disabled = false;
                this.loading = false;
            })
        },
        deleteVehicle(){
            axios.delete(`/user/${this.user_id}`)
            .then(response => {
                $('#deleteModal').modal('hide');
                alert('User successfully deleted');
                this.fetchUsers(this.pagination.current_page || 1);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            })
        },
        setPage(pageNumber) {
            if (pageNumber < 1 || pageNumber > this.pagination.last_page) {
                return;
            }
            this.fetchUsers(pageNumber);
        },

        resetStartRow() {
            this.currentPage = 1;
        },

        showPreviousLink() {
            return this.pagination.current_page > 1;
        },

        showNextLink() {
            return this.pagination.current_page < this.pagination.last_page;
        }   
    },
    beforeDestroy() {
        clearTimeout(this.searchDebounceTimer);
        window.removeEventListener('pageshow', this.handlePageShow);
    }
}
</script>
