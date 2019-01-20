<template>
    <div>
        <message :success="success" :message="message"></message>
        <!-- GalleryForm Component -->  
        <form v-on:submit.prevent="saveGallery(gallery.id)" enctype="multipart/form-data">
            <h1>Edit Gallery</h1>
            <div class="form-group">
                <label for="name">Name of Gallery</label>
                <input type="text" class="form-control" id="gallery-name" aria-describedby="error-message-name" v-model="gallery.name">
                <small id="error-message-name" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.name">{{ message }}</small>
            </div>
            <div class="form-group">
                <label for="descrption">Description</label>
                <textarea style="resize:vertical;" class="form-control" id="gallery-description" aria-describedby="error-message-description" v-model="gallery.description"></textarea>
                <small id="error-message-description" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.description">{{ message }}</small>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    Published?
                    <input type="checkbox" class="form-check-input" v-model="gallery.published">
                </label>
            </div>
            <button type="submit" class="btn btn-success" :disabled="loading == true">Submit</button>
            <button type="reset" class="btn btn-primary" :disabled="loading == true">Reset</button>
        </form>
        <hr>
        <!-- Image List -->
        <h1>Image List</h1> 
        <button class="btn btn-warning" :disabled="loading == true" v-if="gallery.images && gallery.images.length > 0" v-on:click="publishAllImages()">Publish All Images</button>

        <div class="fileUpload btn btn-primary" v-if="!loading">
            <span>Upload images</span>
            <input type="file" multiple="multiple" class="upload" name="thumbnail" accept="image/*" aria-describedby="error-message-file" v-on:change="onImagesChange">
        </div>
        <small v-else>Loading...</small>
        <small id="error-message-file" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.File">{{ message }}</small>
        <div class="row">
            <div v-bind:key="item.id" v-for="item in gallery.images">
                <div class="col-sm-4 col-md-3">
                    <div class="thumbnail">                      
                        <a v-bind:href="item.image">
                            <div class="item-thumbnail" v-html='thumbnail(item.public_id)'></div>
                        </a>
                        <div class="caption">
                            <form v-on:submit.prevent="updateImage(item)" enctype="multipart/form-data">
                                <p>Created at : {{item.created_at}}</p>
                                <p>Updated at : {{item.updated_at}}</p>
                                <p>File Size : {{item.bytes | megabyte}}</p>
                                <p>Original width: {{item.width | pixel}}</p>
                                <p>Original height: {{item.height | pixel}}</p>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input class="form-control" type="text" style="resize:none;" v-model="item.description" placeholder="Description"/>
                                </div>
                                <div class="form-group">
                                    <label>Gallery</label>
                                    <select class="form-control" v-model="item.gallery_id">
                                        <option v-bind:key="x.id" v-bind:value="x.id" v-for="x in list">{{x.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-check-label">
                                        Published?
                                        <input type="checkbox" class="form-check-input" v-model="item.published"/>
                                    </label>                  
                                </div>
                                <div class="form-check">
                                    <button type="submit" class="btn btn-success" :disabled="loading == true">Submit</button>
                                    <button type="reset" class="btn btn-primary" :disabled="loading == true">Reset</button>
                                    <button class="btn btn-danger" v-on:click="deleteImage(item.id)" :disabled="loading == true">Delete</button>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Message from '../components/Message';
import GalleryServices from '../services/GalleryServices';
import ImageServices from '../services/ImageServices'; 
import cl from '../cloudinaryConfig';

export default {
    components: {
        'message': Message,
    },
    filters :{
        megabyte(value) {
            return (value/1024/1024).toPrecision(3) + 'MB';
        },
        pixel(value) {
            return value+ 'px';
        }
    },
    data (){
        return {
            gallery: {
                id: '',
                name: '',
                description: '',
                images: [],
                published: false
            },

            list: [],

            //Errors and messages
            errors: [],
            success: false,
            message: '',
            loading: false
        }
    },
    methods: {
        //Upload the images of gallery
        onImagesChange(e) {
            this.loading = true;
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            for(let i=0;i<files.length;i++){
                this.createImages(files[i]);
            }
            this.loading = false;
        },
        //Create the images on the form
        createImages(file) {
            ImageServices.createImages(file, this.$route.params.id).then(response => { 
                this.images = response.data.results;
                this.message = response.data.message;
                this.success = response.data.success;
                window.scrollTo(0, 0);
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
        //Get the gallery record for detail page
        getGallery(id){
            this.getList();
            GalleryServices.getGallery(id).then(response => {
                this.gallery.published = response.data.published;
                this.gallery.name = response.data.name;
                this.gallery.description = response.data.description;
                this.gallery.message = response.data.message;
                this.gallery.id = id;
                this.gallery.images = response.data.images;
            }).catch(e => {
                alert(e.response.data.errors.message, 'error');
                this.$router.push({ name: 'addGallery' });
            }); 
        },
        //Save gallery record
        saveGallery(id){
            this.loading = true;

            GalleryServices.updateGallery(this.gallery).then(response => {   
                this.success = response.data.success;
                this.message = response.data.message;
                this.loading = false;
                if(response.data.success){
                    this.items = response.data.results;
                    this.resetForm();
                    this.resetFilter();
                }else{
                    this.errors = response.data.errors;
                }
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
        //Delete gallery
        deleteGallery(id){
            let r = confirm("Are you sure to delete this gallery?");
            if (r == true) {
                this.loading = true;
                GalleryServices.deleteGallery(id).then(response => {
                    if(response.data.success){
                        this.items = response.data.results;
                        this.resetFilter();
                    }else{
                        this.errors = response.data.errors;
                    }
                    this.success = response.data.success;
                    this.message = response.data.message;
                    window.scrollTo(0, 0);
                    this.loading = false;
                }).catch(e => {
                    this.errors.push(e)
                }); 
            } 
        },
        //Get the list of gallery for dropdown
        getList(){
            GalleryServices.getGalleryDorpdownList().then(response => { 
                this.list = response.data;
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
        //Update the image record
        updateImage(imageObj){
            this.loading = true;

            ImageServices.updateImage(imageObj).then(response => { 
                this.success = response.data.success;
                this.message = response.data.message;
                window.scrollTo(0, 0);
                this.loading = false;
            })
            .then(() => {
                this.getGallery(imageObj.gallery_id);
            })
            .catch(e => {
                this.errors.push(e);
            }); 
        },
        //Delete the image record
        deleteImage(imageID){
            let r = confirm("Are you sure to delete this image?");
            if (r == true) {
                this.loading = true;
                ImageServices.deleteImage(imageID).then(response => { 
                    this.success = response.data.success;
                    this.message = response.data.message;
                    window.scrollTo(0, 0);
                    this.loading = false;
                })
                .then(() => {
                    this.getGallery(imageObj.gallery_id);
                })
                .catch(e => {
                    this.errors.push(e);
                }); 
            }
        },
        //Publish all image
        publishAllImages(){
            const galleryId = this.$route.params.id
            ImageServices.publishAllImages(galleryId)
            .then(response => { 
                this.success = response.data.success;
                this.message = response.data.message;
                window.scrollTo(0, 0);
                this.loading = false;
            })            
            .then(() => {
                this.getGallery(galleryId);
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
        thumbnail (value){
            return cl.imageTag(value, { width: 150, height: 150, crop: "fill", responsive_class: "img-responsive"}).toHtml();
        }   
    },
    beforeMount : function() {
        this.getList();
        this.getGallery(this.$route.params.id);
    }
}
</script>
<style scoped>
    .item-thumbnail{
        text-align: center
    }
</style>
