$('#country').change(function(){
    $('#state').html('<option value="">Select State</option>');
    $('#city').html('<option value="">Select City</option>');
    if($(this).val()){
        getStates($(this).val());
    }
});

$('#state').change(function(){
    $('#city').html('<option value="">Select City</option>');
    if($(this).val() != ""){
        getCities($(this).val());
    }
})
/*
function getStates(country){
    $.ajax({
            type:"post",
            url:"https://countriesnow.space/api/v0.1/countries/states",
            data:{
                "country": "United States"
            },
            success:function(response){
                const states = response.data.states;
                for(var i=0;i<states.length;i++){
                    // this state does not have cities
                    if(states[i].name != 'American Samoa'){
                        $('#state').append('<option value="'+states[i].name+'"'+(states[i].name == state_name ? ' selected' : '')+'>'+states[i].name+'</option>');

                    }
                }
            }
    });
}
*/

/*Code for countries and state start*/

function getCountries(){
    $.ajax({
        type:"get",
        url:"https://countriesnow.space/api/v0.1/countries/positions",
        success:function(response){
            const countries = response.data;
            for(var i=0;i<countries.length;i++){
                $('#country').append('<option value="'+countries[i]['name']+'">'+countries[i]['name']+'</option>');
            }
        }
    });
}

function getStates(country_name){
    $.ajax({
        type:"post",
        url:"https://countriesnow.space/api/v0.1/countries/states",
        data:{
            "country": country_name,
        },
        success:function(response){
            let excluded = [
                "American Samoa",
                "Baker Island",
                "District of Columbia",
                "Guam",
                "Howland Island",
                "Jarvis Island",
                "Johnston Atoll",
                "Kingman Reef",
                "Midway Atoll",
                "Navassa Island",
                "Northern Mariana Islands",
                "Palmyra Atoll",
                "Puerto Rico",
                "United States Minor Outlying Islands",
                "United States Virgin Islands",
                "Wake Island"];
            const states = response.data.states;
            for(var i=0;i<states.length;i++){
                if(excluded.indexOf(states[i]['name']) === -1)
                    $('#state').append('<option value="'+states[i]['name']+'"'+(states[i]['name'] === state_name ? ' selected' : '')+'>'+states[i]['name']+'</option>');
            }
        }
    });
}

/*Code for countries and state end*/
function getCities(state_name){
    $.ajax({
        type:"post",
        url:"https://countriesnow.space/api/v0.1/countries/state/cities",
        data:{
            "country": "United States",
            "state": state_name
        },
        success:function(response){
            const cities = response.data;
            for(var i=0;i<cities.length;i++){
                $('#city').append('<option value="'+cities[i]+'"'+(cities[i] === city_name ? ' selected' : '')+'>'+cities[i]+'</option>');
            }
        }
    });
}
