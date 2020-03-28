$(() => {

    $("input[type='radio']").checkboxradio();
    
    $('[name="gender"]').on('change', (e) => {
        gender = e.target.value;
    });
    $('[name="seeking"]').on('change', (e) => {
        seeking = e.target.value;
    });

    $("#age-slider-range").slider({
        range: true,
        min: 18,
        max: 70,
        values: [25, 30],
        slide: function (event, ui) {
            $("#filter-age").val(ui.values[0] + " - " + ui.values[1]);
            age_range[0] = ui.values[0];
            age_range[1] = ui.values[1];
        }
    });
    $("#filter-age").val(`${$("#age-slider-range").slider("values", 0)} - ${$("#age-slider-range").slider("values", 1)}`);

    $("#distance-slider-range").slider({
        range: true,
        min: 0,
        max: 45,
        values: [5, 10],
        slide: function (event, ui) {
            $("#filter-distance").val(ui.values[0] + "km - " + ui.values[1] + "km");
            distance_range[0] = ui.values[0];
            distance_range[1] = ui.values[1];
        }
    });
    $("#filter-distance").val(`${$("#distance-slider-range").slider("values", 0)}km - ${$("#distance-slider-range").slider("values", 1)}km`);


    // Populate interests/traits list whe user searches
    //$('#interests-search').on('input', () => {
    $('#interests-search-button').on('click', () => {
        var searchTerm = $('#interests-search').val();

        if (searchTerm.length > 0) {
            $('#interests-search-button').prop('disabled', true);
            $.ajax('search_handler.php', {
                type: 'POST',
                data: { 'search_interests': searchTerm },
                success: (data, status, xhr) => {
                    $('#interests-search-button').prop('disabled', false);
                    if (data == "not_found") {
                        return;
                    }
                    populate_available_interests_grid(JSON.parse(data));
                },
                error: (xhr, status, e) => {
                    $('#interests-search-button').prop('disabled', false);
                    alert("There was an error.");
                }
            });
        }
    });

    // Populate traits/traits list whe user searches
    //$('#traits-search').on('input', () => {
    $('#traits-search-button').on('click', () => {
        var searchTerm = $('#traits-search').val();

        if (searchTerm.length > 0) {
            $('#traits-search-button').prop('disabled', true);
            $.ajax('search_handler.php', {
                type: 'POST',
                data: { 'search_traits': searchTerm },
                success: (data, status, xhr) => {
                    $('#traits-search-button').prop('disabled', false);
                    if (data == "not_found") {
                        return;
                    }
                    populate_available_traits_grid(JSON.parse(data));
                },
                error: (xhr, status, e) => {
                    $('#traits-search-button').prop('disabled', false);
                    alert("There was an error.");
                }
            });
        }
    });

    $('#submit-search-button').on('click', () => {
        submitSearch();
    });
});

// Interests related functions

function populate_available_interests_grid(interests) {
    // Populate available interests grid
    $('#interests-grid').empty();
    for (var i = 0; i < interests.length; i++) {
        console.log(interests);
        var el = available_interests[interests[i]];
        $('#interests-grid').append(`<div class='grid-square' data-toggle='tooltip' data-placement='right' title='${el.name}' onclick='selectInterest(${interests[i]})'><i class='fas ${el.icon}'></i></div>`);
    };
    $('[data-toggle="tooltip"]').tooltip();
}

function populate_selected_interests_list() {
    $('#selected-interests-list').empty();
    jQuery.each(selected_interests, function (i, val) {
        var el = available_interests[val];
        $('#selected-interests-list').append(`<li class='list-group-item' id='interest-item-${val}'><div class='input-group'><div class='input-group-prepend'><i class='btn btn-outline-dark fas ${el.icon}'></i></div><span class='form-control'>${el.name}</span><div class='input-group-append'><button class='btn btn-outline-danger' type='button' onclick='removeInterest(${val})'><i class='fas fa-trash'></i></button></div></div></li>`)
    });
}

function selectInterest(interest_id) {
    if (selected_interests.length >= 5) {
        alert("Only 5 interests at a time.");
        return;
    }
    for (var iid of selected_interests) {
        if (iid == interest_id) {
            // Duplicate interest not allowed
            $('#interest-item-' + interest_id).effect('shake');
            return;
        }
    }

    selected_interests.push(interest_id);
    populate_selected_interests_list();
}

function removeInterest(interest_id) {
    jQuery.each(selected_interests, function (i, val) {
        if (val == interest_id) {
            selected_interests.splice(i, 1);
        }
    });
    populate_selected_interests_list();
}

// Trait related functions

function populate_available_traits_grid(traits) {
    // Populate available traits grid
    $('#traits-grid').empty();
    for (var i = 0; i < traits.length; i++) {
        console.log(traits);
        var el = available_traits[traits[i]];
        $('#traits-grid').append(`<div class='grid-square' data-toggle='tooltip' data-placement='right' title='${el.name}' onclick='selecttrait(${traits[i]})'><i class='fas ${el.icon}'></i></div>`);
    };
    $('[data-toggle="tooltip"]').tooltip();
}

function populate_selected_traits_list() {
    $('#selected-traits-list').empty();
    jQuery.each(selected_traits, function (i, val) {
        var el = available_traits[val];
        $('#selected-traits-list').append(`<li class='list-group-item' id='trait-item-${val}'><div class='input-group'><div class='input-group-prepend'><span class='btn btn-outline-dark fas ${el.icon}'></span></div><span class='form-control'>${el.name}</span><div class='input-group-append'><button class='btn btn-outline-danger' type='button' onclick='removetrait(${val})'><i class='fas fa-trash'></i></button></div></div></li>`)
    });
}

function update_selected_traits_array() {
    selected_traits = [];
    $('#selected-traits-list li').each((idx, li) => {
        var el = $(li);
        // Get traits id from li id
        var iid = el[0]['id'].slice(-1);

        selected_traits.push(iid);
    });
}

function selecttrait(trait_id) {
    if (selected_traits.length >= 5) {
        alert("Only 5 traits at a time.");
        return;
    }
    for (var iid of selected_traits) {
        if (iid == trait_id) {
            // Duplicate trait not allowed
            $('#trait-item-' + trait_id).effect('shake');
            return;
        }
    }

    selected_traits.push(trait_id);
    populate_selected_traits_list();
}

function removetrait(trait_id) {
    jQuery.each(selected_traits, function (i, val) {
        if (val == trait_id) {
            selected_traits.splice(i, 1);
        }
    });
    populate_selected_traits_list();
}

function submitSearch() {
    var data = {
        'gender': gender,
        'seeking': seeking,
        'age-range': age_range,
        'distance-range': distance_range,
        'interests': selected_interests,
        'traits': selected_traits
    }

    console.log(data);

    $('#submit-search-button').prop('disabled', true);
    $.ajax('search_handler.php', {
        type: 'POST',
        data: { 'search_data': data },
        success: (data, status, xhr) => {
            $('#submit-search-button').prop('disabled', false);
            if(data.substring(0,5) == "error"){
                alert(data);
                return;
            }
            populate_results(JSON.parse(data));
        },
        error: (xhr, status, e) => {
            $('#submit-search-button').prop('disabled', false);
            alert("There was an error.");
        }
    });
}

function populate_results(d) {
    d.sort(function(a, b){
        return b.score - a.score;
    });

    $('#search-results-list').empty();
    jQuery.each(d, (index, user) => {
        $('#search-results-list').append(`
            <li class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div class="col-4">
                    <img src="${user.photo}" class="img-fluid rounded" alt="Profile Picture">
                </div>
                <div class="col-8">
                    ${user.firstname} - ${user.age}
                    <p><small>${user.gender} -> ${user.seeking}</small></p>
                    <span class="badge badge-info badge-pill">${user.score}</span>
                </div>
            </li>
        `);
    });
}