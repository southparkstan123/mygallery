<template>
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Weather Widget</div>
            <div class="panel-body">
                <form class="form-horizontal" v-on:submit.prevent="getWeatherInformation()">
                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Location</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" v-model="location" placeholder="Location">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                            <small class="help-block form-text text-muted error-message">{{error}}</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <img :src='icon | iconLink'>
                            <span>{{ temperature.value }}</span>
                            <span>{{ humidity.value }}{{ humidity.units }}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import WeatherServices from '../services/WeatherServices'

export default {
    data (){
        return {
            icon: '',
            humidity: {},
            temperature: {},
            location: '',
            error: ''
        }
    },
    filters: {
        iconLink(value) {
            return value ? 'http://openweathermap.org/img/w/' + value + '.png' : ''
        }
    },
    methods: {
        getWeatherInformation(){
            this.error = '';
            WeatherServices.getWeatherInformation(this.location).then(response => { 
                if(response.data.success){
                    this.city = response.data.city;
                    this.icon = response.data.weather.icon;
                    this.humidity = response.data.humidity;
                    this.temperature = response.data.temperature;
                }else{
                    this.icon = '';
                    this.humidity = {};
                    this.temperature = {};
                    this.error = response.data.errors.location[0];
                }
            }).catch(e => {
                this.errors.push(e);
            }); 
        }
    }
}
</script>