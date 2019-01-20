<template>
    <div>
        <div class="row">
            <div class="text-center">
                <h1>Welcome to MyGallery!</h1>
            </div>
            <weather-widget></weather-widget>
        </div>
    </div>
</template>
<script>
import WeatherWidget from '../components/WeatherWidget';
export default {
    components:{
        'weather-widget': WeatherWidget
    }
}
</script>
