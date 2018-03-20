<template>
    <!-- GalleryList Component -->   
    <div>
        <!-- Message Component -->
        <message v-show="success === true" :success="success" :message="message"></message>
        <div class="row">
            <h1>Gallery List</h1>
        </div>
        <div class="row">
            <div class="btn-group">
                <button class="btn btn-default" v-on:click="filterPublishedGallery(true)">Published</button>
                <button class="btn btn-default" v-on:click="filterPublishedGallery(false)">Unpublished</button>
                <button class="btn btn-default" v-on:click="resetFilter()">Reset</button>
                <button class="btn btn-primary" v-on:click="routeToAddGallery()">Add Gallery</button>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Number of photos</th>
                            <th>Is Published?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-bind:key="item.id" v-for="item in filteredItems">
                            <td>{{ item.name }}</td>
                            <td>{{ item.created_at }}</td>
                            <td>{{ item.updated_at }}</td>
                            <td>{{ item.images.length }}</td>
                            <td>
                                <span class="glyphicon" v-bind:class="{ 'glyphicon-ok': item.published, 'glyphicon-remove': !item.published }" aria-hidden="true"></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-info btn-xs" v-on:click="routeToGalleryForm(item.id)" :disabled="loading == true"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                    <button class="btn btn-danger btn-xs" v-on:click="deleteGallery(item.id)" :disabled="loading == true"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import Message from './Message';
    export default {
        components:{
            'message': Message
        },
        data () {
            //Gallery list
            let items = [];
            let filteredItems = [];

            // Errors and messages
            let errors = [];
            let success = false;
            let message = '';
            let loading = false;

            return {
                //Gallery list
                items: items,
                filteredItems: filteredItems,

                //Errors and messages
                errors: errors,
                success: success,
                message: message,
                loading: loading
            }
        },
        methods: {
            //Get the list of gallery for listing page
            getGalleries(){

                const config = {
                    headers: { 
                        'Content-Type': 'application/json' ,
                        'Authorization': 'Bearer ' + localStorage.getItem('token'),
                    }
                };

                axios.get('/api/v1/gallery',config).then(response => {
                    this.items = response.data;
                    this.resetFilter();
                }).catch(e => {
                    console.log(e);
                }); 
            },
            routeToAddGallery(){
                var path = '/gallery_add';
                this.$router.push({ path: path});
            },
            routeToGalleryForm(id){
                var path = '/galleries/' + id;
                this.$router.push({ path: path});
            },
            deleteGallery(id){
                let r = confirm("Are you sure to delete this gallery?");
                if (r == true) {
                    this.loading = true;
                    axios.delete('/api/v1/gallery/delete/' + id).then(response => {
                        if(response.data.success){
                            this.items = response.data.results;
                            this.resetFilter();
                        }else{
                            this.errors = response.data.errors;
                        }
                        this.success = response.data.success;
                        this.message = response.data.message;
                        this.loading = false;
                    }).catch(e => {
                        this.errors.push(e)
                    }); 
                } 
            },
            filterPublishedGallery(is_published){
                let items = this.items;
                this.filteredItems = items.filter((item) => {
                    return is_published ? item.published : !item.published
                });
            },
            resetFilter(){
                this.filteredItems = this.items;
            },
        },
        mounted : function () {
            this.resetFilter();
            this.getGalleries();
        }
    }
</script>