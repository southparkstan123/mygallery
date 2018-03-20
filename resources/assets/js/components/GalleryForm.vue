<template>
    <div>
        <message :success="success" :message="message"></message>
        <!-- GalleryForm Component -->  
        <form v-on:submit.prevent="saveGallery(id)" enctype="multipart/form-data">
            <h1>Edit Gallery</h1>
            <div class="form-group">
                <label for="name">Name of Gallery</label>
                <input type="text" class="form-control" id="gallery-name" aria-describedby="error-message-name" v-model="name">
                <small id="error-message-name" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.name">{{ message }}</small>
            </div>
            <div class="form-group">
                <label for="descrption">Description</label>
                <textarea style="resize:vertical;" class="form-control" id="gallery-description" aria-describedby="error-message-description" v-model="description"></textarea>
                <small id="error-message-description" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.description">{{ message }}</small>
            </div>
            <!-- <div class="form-group">
                <div class="fileUpload btn btn-primary">
                    <span>Select an image</span>
                    <input type="file" class="upload" :disabled="loading == true" name="thumbnail" accept="image/*" aria-describedby="error-message-file" v-on:change="onThumbnailChange">
                </div>
                <small id="error-message-file" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.File">{{ message }}</small>
            </div> -->
            <!-- <div class="form-group">
                <div v-show="previewImage">
                    <label for="name">Thumbnail Preview</label>
                    <img :src="previewImage" class="img-responsive">
                </div>
                <div v-show="thumbnail">
                    <label for="name">Original Thumbnail</label>
                    <img :src="thumbnail" class="img-responsive">
                </div>
            </div> -->
            <div class="form-check">
                <label class="form-check-label">
                    Published?
                    <input type="checkbox" class="form-check-input" v-model="published">
                </label>
            </div>
            <button type="submit" class="btn btn-success" :disabled="loading == true">Submit</button>
            <button type="reset" class="btn btn-primary" :disabled="loading == true">Reset</button>
        </form>
        <hr>
        <!-- Image List -->
        <h1>Image List</h1> 
        <button class="btn btn-warning" :disabled="loading == true" v-if="images && images.length > 0" v-on:click="publishAllImages()">Publish All Images</button>

        <div class="fileUpload btn btn-primary" v-if="!loading">
            <span>Upload images</span>
            <input type="file" multiple="multiple" class="upload" name="thumbnail" accept="image/*" aria-describedby="error-message-file" v-on:change="onImagesChange">
        </div>
        <small v-else>Loading...</small>
        <small id="error-message-file" class="form-text text-muted error-message" v-bind:key="message" v-for="message in errors.File">{{ message }}</small>
        <div class="row">
            <div v-bind:key="item.id" v-for="item in images">
                <div class="col-sm-4 col-md-3">
                    <div class="thumbnail">                      
                        <a v-bind:href="item.image">
                            <img :src="'http://res.cloudinary.com/futyau/image/upload/c_fill,h_150,w_150/'+item.public_id" class="img-responsive">  
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
                                    <textarea class="form-control" rows="6" style="resize:none;" v-model="item.description" placeholder="Description"></textarea>
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
                                        <input type="checkbox" class="form-check-input" v-model="item.published">
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
import Message from './Message';
export default {
    components: {
        'message': Message,
    },
    filters :{
        megabyte :function (value) {
            return (value/1024/1024).toPrecision(3) + 'MB';
        },
        pixel :function (value) {
            return value+ 'px';
        }
    },
    data (){
        //Add and edit gallery 
        let published = false;
        let name = '';
        let description = '';
        let file = {};
        let id = this.$route.params.id;
        // let thumbnail = '';
        // let previewImage = '';
        let images = [];
        let list = [];

        // Errors and messages
        let errors = [];
        let success = false;
        let message = '';
        let loading = false;

        return {
            //Add and edit gallery 
            id: id,
            published: published,
            // thumbnail: thumbnail,
            name: name,
            description: description,
            // file: file,
            // previewImage: previewImage,
            images: images,
            list: list,

            //Errors and messages
            errors: errors,
            success: success,
            message: message,
            loading: loading
        }
    },
    methods: {
        //Upload the thumbnail of gallery
        // onThumbnailChange(e) {
        //     let files = e.target.files || e.dataTransfer.files;
        //     if (!files.length)
        //         return;
        //     this.createImage(files[0]);
        // },
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
        //Show the preview thumbnail on the form
        // createImage(file) {
        //     let reader = new FileReader();
        //     let vm = this;
        //     reader.onload = (e) => {
        //         vm.previewImage = e.target.result;
        //         vm.file = file;
        //     };
        //     reader.readAsDataURL(file);
        // },
        //Create the images on the form
        createImages(file) {
            let formData = new FormData();
            formData.append('File',file);
            formData.append('gallery_id',this.$route.params.id);

            const config = {
                headers: { 
                    'Content-Type': 'multipart/form-data' , 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };
            axios.post('/api/v1/images/create', formData, config).then(response => { 
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
            axios.get('/api/v1/gallery/' + id).then(response => {
                this.published = response.data.published;
                this.name = response.data.name;
                this.description = response.data.description;
                this.message = response.data.message;
                this.id = id;
                // this.thumbnail = response.data.thumbnail;
                this.images = response.data.images;
            }).catch(e => {
                //console.log(localStorage.getItem('token'));
                alert(e.response.data.errors.message, 'error');
                this.$router.push({ name: 'addGallery' });
            }); 
        },
        //Save gallery record
        saveGallery(id){
            this.loading = true;
            
            let formData = new FormData();

            // formData.append('File',this.file);
            formData.append('name',this.name);
            formData.append('published',this.published);
            formData.append('description',this.description);

            const config = {
                headers: { 'Content-Type': 'multipart/form-data' }
            }
            this.loading = true;
            axios.post('/api/v1/gallery/update/' + id, formData, config).then(response => {   
                this.success = response.data.success;
                this.message = response.data.message;
                this.loading = false;
                if(response.data.success){
                    console.log(response.data.message);
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
                axios.delete('/api/v1/gallery/delete/' + id).then(response => {
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
            axios.get('/api/v1/list_gallery').then(response => { 
                this.list = response.data;
            }).catch(e => {
                this.errors.push(e);
            }); 
        },
        //Update the image record
        updateImage(imageObj){
            this.loading = true;
            let formData = new FormData();

            formData.append('published',imageObj.published);
            formData.append('description',imageObj.description);
            formData.append('gallery_id',imageObj.gallery_id);

            const config = {
                headers: { 'Content-Type': 'multipart/form-data' }
            }

            axios.post('/api/v1/images/update/' + imageObj.id, formData, config)
            .then(response => { 
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
                axios.delete('/api/v1/images/delete/' + imageID)
                .then(response => { 
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

            let formData = new FormData();
            formData.append('gallery_id',this.$route.params.id);

            const config = {
                headers: { 'Content-Type': 'multipart/form-data' }
            }

            axios.post('/api/v1/images/publishAll/', formData, config).then(response => { 
                this.success = response.data.success;
                this.message = response.data.message;
                window.scrollTo(0, 0);
                this.loading = false;
            })            
            .then(() => {
                this.getGallery(imageObj.gallery_id);
            }).catch(e => {
                this.errors.push(e);
            }); 
        }   
    },
    beforeMount : function() {
        this.getList();
        this.getGallery(this.id);
    }
}
</script>