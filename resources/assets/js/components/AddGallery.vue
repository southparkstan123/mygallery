<template>
    <div>
        <!-- AddGalleryForm Component -->  
        <form v-on:submit.prevent="createGallery()" enctype="multipart/form-data">
            <message :success="success" :message="message"></message>
            <h1>Add Gallery</h1>
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
                    <input type="file" class="upload" name="thumbnail" accept="image/*" aria-describedby="error-message-file" v-on:change="onThumbnailChange">
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
    </div>
</template>
<script>
import Message from './Message';
export default {
    components: {
        'message': Message
    },
    data (){
        //Add and edit gallery 
        let published = false;
        let name = '';
        let description = '';
        // let file = {};
        // let thumbnail = '';
        // let previewImage = '';

        // Errors and messages
        let errors = [];
        let success = false;
        let message = '';
        let loading = false;

        return {
            //Add and edit gallery 
            published: published,
            // thumbnail: thumbnail,
            name: name,
            description: description,
            // file: file,
            // previewImage: previewImage,

            //Errors and messages
            errors: errors,
            success: success,
            message: message,
            loading: loading
        }
    },
    methods: {
        resetForm(){
            this.published = false;
            this.name = '';
            this.description = '';
            // this.file = {};
            // this.thumbnail = '';
            // this.previewImage = '';
            this.loading = false;
        },
        //Upload the thumbnail of gallery
        // onThumbnailChange(e) {
        //     let files = e.target.files || e.dataTransfer.files;
        //     if (!files.length)
        //         return;
        //     this.createImage(files[0]);
        // },
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

        //Save or update gallery record
        createGallery(){
            this.loading = true;
            let formData = new FormData();

            // formData.append('File',this.file);
            formData.append('name',this.name);
            formData.append('published',this.published);
            formData.append('description',this.description);

            const config = {
                headers: { 'Content-Type': 'multipart/form-data' }
            }

            axios.post('/api/v1/gallery/create/', formData, config).then(response => { 
                if(response.data.success){
                    this.items = response.data.results;
                    this.resetForm();
                    this.$router.push({ name: 'listGalleries'});
                }else{
                    this.errors = response.data.errors;
                }
                this.success = response.data.success;
                this.message = response.data.message;
                this.loading = false;
                
            }).catch(e => {
                this.errors.push(e);
            }); 
        } 
    },
    mounted: function(){
        this.resetForm();
    }
}
</script>

    