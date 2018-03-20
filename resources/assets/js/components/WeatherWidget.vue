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
                            <img :src='icon'>
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
export default {
    data (){
        let icon = '';
        let humidity = {};
        let temperature = {};
        let location = '';
        let error = '';
        return {
            icon: icon,
            humidity: humidity,
            temperature: temperature,
            location: location,
            error: error
        }
    },
    methods: {
        getWeatherInformation(location){
            let formData = new FormData();
            formData.append('location',this.location);
            this.error = '';
            axios.post('api/v1/getWeather/',formData).then(response => { 
                if(response.data.success){
                    this.city = response.data.city;
                    this.icon = 'http://openweathermap.org/img/w/' + response.data.weather.icon + '.png';
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
    // export default {
    //     props: ['icon', 'temperature', 'humidity'],
    //     // filters: {
    //     //     units: function (temp) {
    //     //         return temp.value + decodeURIComponent(temp.units);
    //     //     }
    //     // }
    //     // methods: {
    //     //     getWeatherInformation(){
    //     //         const OPEN_WEATHER_MAP_URL = 'http://api.openweathermap.org/data/2.5/weather?';

    //     //         var encodedLocation = encodeURIComponent('HongKong');
    //     //         var apiKey = 'baa7504a87bfb3f4ec041d088ab8e218';
    //     //         var units = 'metric';
    //     //         var requestURL = `${OPEN_WEATHER_MAP_URL}q=${encodedLocation}&appid=${apiKey}&units=${units}`;

    //     //         var config ={
    //     //             headers: {
    //     //                 'Access-Control-Allow-Origin': '*',
    //     //                 'Access-Control-Allow-Methods': '*',
    //     //                 'Access-Control-Allow-Headers': '*'
    //     //             }
    //     //         };

    //     //         axios.get(requestURL,config).then(response => { 
    //     //             console.log(response.data);
    //     //             this.data = response.data;
    //     //         }).catch(e => {
    //     //             this.errors.push(e);
    //     //         }); 
    //     //     }
    //     // },
    //     // mounted: function(){
    //     //     this.getWeatherInformation();
    //     // }
    // }
</script>