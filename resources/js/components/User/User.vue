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
                                    <input type="text" class="form-control" placeholder="Search" v-model="keywords" id="keywords">
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
                                    <tr v-for="(user, u) in filteredQueues" v-bind:key="u">
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#editModal" style="cursor: pointer" @click="copyObject(user)">Edit</a>
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" style="cursor: pointer" @click="copyObject(user)">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td scope="row">{{ user.id }}</td>
                                        <td>{{ user.name }}</td>
                                        <td>{{ user.email }}</td>
                                        <td>{{ user.roles[0].name }}</td>
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
                                <span>{{ filteredQueues.length }} User(s)</span>
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
                        <div class=row>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role">Role*</label> 
                                    <select class="form-control" v-model="user.role">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">The indicator field is required</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Based Trucks</label>
                                    <multiselect
                                            v-model="user.based_trucks"
                                            :options="based_trucks"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabel"
                                            placeholder="Select based_trucks"
                                        >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.based_trucks">{{ errors.based_trucks[0] }}</span>
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
                                    <select class="form-control" v-model="copied_role">
                                        <option v-for="(role,r) in roles" v-bind:key="r" :value="role.id"> {{ role.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="errors.role">{{ errors.role[0] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="role">Based Trucks</label>
                                    <multiselect
                                            v-model="user_copied.based_trucks"
                                            :options="based_trucks"
                                            :multiple="true"
                                            track-by="id"
                                            :custom-label="customLabel"
                                            placeholder="Select based_trucks"
                                        >
                                    </multiselect>
                                    <span class="text-danger" v-if="errors.based_trucks">{{ errors.based_trucks[0] }}</span>
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
            errors: [],
            currentPage: 0,
            itemsPerPage: 50,
            keywords: '',
            loading: false,
            user_added: false,
            user_updated: false,
            user_id: ''
        }
    },
    created(){
        this.fetchUsers();
        this.fetchRoles();
        this.fetchBasedTrucks();
    },
    methods:{
        customLabel (based_truck) {
            return `${based_truck.description  }`
        },
        copyObject(user){
            this.user_copied = Object.assign({}, user);
            this.copied_role = this.user_copied.roles[0].id;
            this.user_id = user.id;
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
        fetchRoles(){
            axios.get('/roles')
            .then(response => { 
                this.roles = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        fetchUsers(){
            axios.get('/users-all')
            .then(response => { 
                this.users = response.data;
            })
            .catch(error => { 
                this.errors = error.response.data.error;
            })
        },
        resetForm(){
            this.errors = [];
            this.user = [];
        },
        addUser(user){
            var based_trucks_ids = [];
            user.based_trucks.forEach((based_truck) => {
                based_trucks_ids.push(based_truck.id);
            });
        
            this.user_added = false;
            this.loading = true;
            document.getElementById('add_btn').disabled = true;

            axios.post('/user', {
                name: user.name,
                email: user.email,
                password: user.default_password,
                role: user.role,
                based_trucks: based_trucks_ids
            })
            .then(response =>{
                this.user_added = true;
                this.users.unshift(response.data);
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

            var based_trucks_ids = [];
            user_copied.based_trucks.forEach((based_truck) => {
                based_trucks_ids.push(based_truck.id);
            });

            this.edit_updated = false;
            this.loading = true;
            document.getElementById('edit_btn').disabled = true;
            var index = this.users.findIndex(item => item.id == user_copied.id);

            axios.post(`/user/${user_copied.id}`, {
                name: user_copied.name,
                email: user_copied.email,
                password: user_copied.default_password,
                role: copied_role,
                based_trucks: based_trucks_ids,
                _method: 'PATCH'
            })
            .then(response => {
                this.user_updated = true;
                this.users.splice(index,1,response.data);
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
            var index = this.users.findIndex(item => item.id == this.user_id);
            axios.delete(`/user/${this.user_id}`)
            .then(response => {
                $('#deleteModal').modal('hide');
                alert('User successfully deleted');
                this.users.splice(index,1);
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
        filteredUsers(){
            let self = this;
            return Object.values(self.users).filter(user => {
                return user.name.toLowerCase().includes(this.keywords.toLowerCase())
            });
        },
        totalPages() {
            return Math.ceil(Object.values(this.users).length / this.itemsPerPage)
        },
        filteredQueues() {
            var index = this.currentPage * this.itemsPerPage;
            var queues_array = this.filteredUsers.slice(index, index + this.itemsPerPage);

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
