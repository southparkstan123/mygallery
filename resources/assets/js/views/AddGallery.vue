<template>
    <div>
        <!-- AddGalleryForm Component -->  
        <form v-on:submit.prevent="createGallery()" enctype="multipart/form-data">
            <message :success="success" :message="message"></message>
            <h1>Add Gallery</h1>
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
    </div>
</template>
<script>
import Message from '../components/Message';
import GalleryService from '../services/GalleryServices'

export default {
    components: {
        'message': Message
    },
    data (){
        return {
            gallery: {
                name: '',
                description: '',
                published: false
            },
            errors: [],
            success: false,
            message: '',
            loading: false
        }
    },
    methods: {
        resetForm(){
            this.gallery.published = false;
            this.gallery.name = '';
            this.gallery.description = '';
            this.loading = false;
        },

        //Save or update gallery record
        createGallery(){
            this.loading = true;
            GalleryService.createGallery(this.gallery).then(response => { 
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

    