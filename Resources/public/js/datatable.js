require('datatables.net');
import moment from 'moment';

var table;
var moment_locale = 'en-au';

// https://stackoverflow.com/questions/8790607/javascript-json-get-path-to-given-subnode
/**
 * Converts a string path to a value that is existing in a json object.
 *
 * @param {Object} jsonData Json data to use for searching the value.
 * @param {Object} path the path to use to find the value.
 * @returns {valueOfThePath|undefined}
 */
function jsonPathToValue(jsonData, path) {
    if (!(jsonData instanceof Object) || typeof (path) === "undefined") {
        throw "Not valid argument:jsonData:" + jsonData + ", path:" + path;
    }
    path = path.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    path = path.replace(/^\./, ''); // strip a leading dot
    var pathArray = path.split('.');
    for (var i = 0, n = pathArray.length; i < n; ++i) {
        var key = pathArray[i];
        if (key in jsonData) {
            if (jsonData[key] !== null) {
                jsonData = jsonData[key];
            } else {
                return null;
            }
        } else {
            return key;
        }
    }
    return jsonData;
}
//

window.DatatableRenderObjects = {};

$(document).ready(function () {
    var serverConfig = JSON.parse(document.getElementById("datatable-config").textContent);
    moment_locale = serverConfig.moment_locale;
    var config = {
        "drawCallback": function( settings ) {
            var api = false;
            var json = false;
            if (serverConfig.datatables.serverSide) {
                api = this.api();
                json = api.ajax.json();
            } else {
                json = table.ajax.json()
            }
            var lastFilteredCol = handleStorageItem('get', 'lastFilteredCol');
            var firstFilteredCol = handleStorageItem('get', 'firstFilteredCol');

            var newColumnFilterChoices = {};
            for (var target in json.columnFilterChoices) {
                var i = $("th[data-filter-property='"+target+"']").data('filter-target');
                if (lastFilteredCol != null && i == lastFilteredCol.index) {
                    newColumnFilterChoices[target] = lastFilteredCol.options;
                } else if (firstFilteredCol != null && i == firstFilteredCol.index) {
                    newColumnFilterChoices[target] = firstFilteredCol.options;
                } else {
                    if (serverConfig.datatables.serverSide) {
                        newColumnFilterChoices[target] = json.columnFilterChoices[target];
                    } else {
                        var filterChoices = table.column(i).data().unique().toArray();
                        newColumnFilterChoices[target] = filterChoices.filter(function(v){return v!==''});
                    }
                }
            }
            json.columnFilterChoices = newColumnFilterChoices;

            for (target in json.columnFilterChoices) {
                var th = $("th[data-filter-property='"+target+"']");
                var searches = getSearches(th, table.settings());

                th.find('select').html(getFilterOptions(th, json, target, searches));
            }

        },

        "initComplete": function( settings, json ) {
            for (var target in json.columnFilterChoices) {
                var th = $("th[data-filter-property='"+target+"']", this);
                var searches = getSearches(th, settings);

                var output = '<select class="dt_columnFilter dt_input" style="width:100%;">';
                output += getFilterOptions(th, json, target, searches);
                output += '</select>';
                var contents = th.html();
                var element = $("<span class='dt_label'></span>");
                element.html(contents);
                th.html(element);
                th.append(output);
            }

            $("th[data-filter-type='text']").each(function(el){
                var searches = getSearches(this, settings);
                var element = $("<span class='dt_label'></span>");
                element.html($(this).html());
                $(this).html(element);
                $(this).append("<input type='text' class='dt_columnFilter dt_input' style='width:100%;' value='"+searches[0]+"'>");
            });

            $('.dt_columnFilter').on('click', function(e){
                e.stopPropagation();
            });

            $('.dt_columnFilter').on('change keyup', function(event){
                if (event.type == 'keyup' && event.which != 13) {
                    return false;
                }

                var $this = $(this);
                var colIndex = $this.closest('th').data('filter-target');
                var options = [];
                $this.closest('th').find('select option').each(function() {
                    options.push($(this).val());
                });

                var filterOptions = { 'index': colIndex, 'options': options.filter(function(v){return v!==''}) };
                saveColumnFilters($this.val(), colIndex, filterOptions);
                filterDtColumn($this.val(), colIndex)
            });
        }
    };

    $.extend(config, serverConfig.datatables);

    serverConfig.columns.forEach(v => {
        if (v.className == 'multiselect') {
            v.render = render_multiselect;
            return;
        }
        if (v.className == 'timeago') {
            v.render = render_timeago;
            return;
        }
        if (v.className == 'link') {
            v.render = (data, type, full) => {
                var routeParams = {};
                if (!Array.isArray(v.routeParameters)) {
                    Object.keys(v.routeParameters).forEach(key => {
                        var val = jsonPathToValue(full, v.routeParameters[key]);
                        if (!val) return false;
                        routeParams[key] = val;
                    });
                }
                if (Object.keys(routeParams).length <= 0) return "";
                if (!Array.isArray(v.staticParameters)) {
                    routeParams = Object.assign(routeParams, v.staticParameters);
                }
                var route = Routing.generate(v.route, routeParams);
                var attributes = Object.keys(v.attributes).map(a => a + '="' + v.attributes[a] + '"').join(" ");
                return "<a href='" + route + "' " + attributes + ">" + data + "</a>";
            };
            return;
        }
        if (v.className == 'boolean') {
            v.render = (data, type, full) => {
                return render_boolean_icons(data, type, full, v.true_icon, v.false_icon, v.true_label, v.false_label);
            };
            return;
        }
        if (v.className == 'datetime') {
            v.render = (data, type, full) => render_datetime(data, type, full, v.localizedFormat);
            return;
        }
        if (v.render && window.DatatableRenderObjects.hasOwnProperty(v.render) && typeof v.render == 'string') {
            let fnIndex = v.render;
            v.render = (data, type, full) => window.DatatableRenderObjects[fnIndex](data, type, full, v.extra_data);
        }
        if (v.seedMapFn && window.DatatableRenderObjects.hasOwnProperty(v.seedMapFn) && typeof v.seedMapFn == 'string') {
            let fnIndex = v.seedMapFn;
            v.seedMapFn = (val) => window.DatatableRenderObjects[fnIndex](val, v.extra_data);
        }
    });

    $.extend(config, {
        "columns": serverConfig.columns,
    });

    if (serverConfig.clearExistingState) {
        handleStorageItem('remove', 'table');
        handleStorageItem('remove', 'firstFilteredCol');
        handleStorageItem('remove', 'lastFilteredCol');
    }

    var selector = "#" + serverConfig.tableId;
    table = $(selector).DataTable(config);

    function filterDtColumn(filter, colIndex) {
        table.column(colIndex).search(filter).draw();
    }

    function saveColumnFilters(filter, colIndex, filterOptions) {
        var firstFilteredCol = handleStorageItem('get', 'firstFilteredCol');

        if (filter != '') {
            handleStorageItem('set', 'lastFilteredCol', filterOptions);

            if (firstFilteredCol == null) {
                handleStorageItem('set', 'firstFilteredCol', filterOptions);
            }
        } else {
            if (firstFilteredCol != null && colIndex == firstFilteredCol.index) {
                handleStorageItem('remove', 'firstFilteredCol');
            } else {
                var lastFilteredCol = handleStorageItem('get', 'lastFilteredCol');
                if (lastFilteredCol != null && colIndex == lastFilteredCol.index) {
                    handleStorageItem('remove', 'lastFilteredCol');
                }
            }
        }
    }

    function getSearches( element, settings ) {
        var dtSearchColIndex = $(element).attr('data-filter-target');
        var searches = table.columns(dtSearchColIndex).search();
        if (searches[0] === 'true') searches[0] = true;
        if (searches[0] === 'false') searches[0] = false;
        return searches;
    }

    function getFilterOptions(element, json, target, searches) {
        var $this = element;

        //add blank row
        var output = '<option value=""></option>';
        json.columnFilterChoices[target].forEach(function(entry){
            var selected = '';
            if (searches[0] !== '' && searches[0] == entry) {
                selected = ' selected="selected"';
            }

            var displayValue = entry;

            var trueValue = $this.data('filter-label-true');
            if (trueValue != 'undefined' && entry == 1) {
                displayValue = trueValue;
            }

            var falseValue = $this.data('filter-label-false');
            if (falseValue != 'undefined' && entry == 0) {
                displayValue = falseValue;
            }

            var colData = table.settings()[0].aoColumns[$this.data('filter-target')];
            if (colData.hasOwnProperty('seedMapFn') && colData.seedMapFn) {
              displayValue = colData.seedMapFn(entry);
            }

            output += '<option value="'+entry+'"'+selected+'>' + displayValue +'</option>';
        })

        return output;
    }

    $(".multiselect_checkall").click(function(event) {
        if(this.checked) {
            $("input:checkbox.multiselect_checkbox").each(function() {
                this.checked = true;
            });
        } else {
            $("input:checkbox.multiselect_checkbox").each(function() {
                this.checked = false;
            });
        }
    });

    function handleStorageItem(action, key, value) {
        var storageKeys = [];
        storageKeys['table'] = 'DataTables_' + serverConfig.tableId + '_' + window.location.pathname;
        storageKeys['lastFilteredCol'] = 'Datatables_' + serverConfig.tableId + '_lastFilteredCol';
        storageKeys['firstFilteredCol'] = 'Datatables_' + serverConfig.tableId + '_firstFilteredCol';

        var useLS = true;
        if (serverConfig.datatables.stateDuration == -1) {
            useLS = false;
        }

        if (action == 'remove') {
            if (useLS) {
                localStorage.removeItem(storageKeys[key]);
            } else {
                sessionStorage.removeItem(storageKeys[key]);
            }
        }

        if (action == 'get') {
          var returnValue = '';
            if (useLS) {
                returnValue = JSON.parse(localStorage.getItem(storageKeys[key]));
            } else {
                returnValue = JSON.parse(sessionStorage.getItem(storageKeys[key]));
            }
            if (returnValue === 'true') returnValue = true;
            if (returnValue === 'false') returnValue = false;
        }

        if (action == 'set' && value != 'undefined' && value != null) {
            if (useLS) {
                localStorage.setItem(storageKeys[key], JSON.stringify(value));
            } else {
                sessionStorage.setItem(storageKeys[key], JSON.stringify(value));
            }
        }
    }

    if (serverConfig.clearStateEnabled) {
        var clearBtn = '<div id="' + serverConfig.tableId + '_clearbtn" class="dataTables_clearbtn"><button type="button" class="btn btn-xs">Reset filters</button></div>';
        $(selector).closest('.dataTables_wrapper').find('.dt_cb').replaceWith(clearBtn);

        $(selector+'_wrapper').on('click', '.dataTables_clearbtn', function(event) {
            handleStorageItem('remove', 'table');
            handleStorageItem('remove', 'firstFilteredCol');
            handleStorageItem('remove', 'lastFilteredCol');

            location.reload();
        });
    }

    $(selector + " tbody").on('click', 'td.expandable', function(){
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var column = $(this).closest('table').find('th').eq($(this).index());
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            var columnIndex = table.column(column).index();
            var settingData = table.settings()[0].aoColumns[columnIndex];
            tr.addClass('shown');
            row.child(settingData.renderContent(row.data())).show();
        }
    })

});

function getSelectedIds() {
    var ids = new Array();
    $("input:checkbox:checked.multiselect_checkbox").each(function(index, el){
        ids.push($(el).val());
    })
    return ids;
}

function render_boolean(data, type, full) {

}

function render_boolean_icons(data, type, full, trueIcon, falseIcon, trueLabel, falseLabel) {
    if (!trueLabel) {
        trueLabel = 'true';
    }

    if (!falseLabel) {
        falseLabel = 'false';
    }

    if (data == true) {
        return "<span class='" + trueIcon + "'></span> " + trueLabel;
    } else {
        return "<span class='" + falseIcon + "'></span> " + falseLabel;
    }
}

function render_datetime(data, type, full, localizedFormat) {
    if (data && typeof data != 'undefined') {
        moment.locale(moment_locale);
        return moment.unix(data).format(localizedFormat);
    } else {
        return null;
    }
}

function render_timeago(data, type, full) {
    if (data && typeof data != 'undefined') {
        moment.locale(moment_locale);
        return moment.unix(data).fromNow();
    } else {
        return null;
    }
}

function render_multiselect(data, type, full) {
    var first;
    for (var i in full) {
        if (full.hasOwnProperty(i) && typeof(i) !== 'function') {
            first = full[i];
            break;
        }
    }
    return "<input type='checkbox' name='multiselect_checkbox' value='" + first + "' class='multiselect_checkbox' />";
}
