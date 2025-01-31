<div id="launchdaemons-tab"></div>

<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/launchdaemons/launchdaemons" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/launchdaemons/launchdaemons_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2 data-i18n="launchdaemons.launchdaemons"></h2>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/launchdaemons/get_tab_data/' + serialNumber, function(data){
        // Set count of launchdaemons
        $('#launchdaemons-cnt').text(data.length);
        var skipThese = ['id','serial_number','label'];
        var $launchdaemonsTab = $('#launchdaemons-tab'); // Cache jQuery selector

        $.each(data, function(i, d){
            var rows = [];
            for (var prop in d){
                if(skipThese.indexOf(prop) === -1){
                    if ((d[prop] === '' || d[prop] === null) && d[prop] !== "0"){
                        continue; // Skip empty values
                    } else if((prop === "startinterval") && +d[prop] >= 60){
                        rows.push('<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td><span title="'+d[prop]+' '+i18n.t('launchdaemons.seconds')+'">'+moment.duration(+d[prop], "seconds").humanize()+'</span></td></tr>');
                    } else if((prop === 'disabled' || prop === 'ondemand' || prop === 'runatload' || prop === 'startonmount' || prop === 'keepalive')){
                        rows.push('<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+i18n.t(d[prop] ? 'yes' : 'no')+'</td></tr>');
                    } else if(prop === 'daemon_json'){
                        rows.push('<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop].replace(/\n\s+/g, function(match) {
                            return '<br>' + '&nbsp;'.repeat(match.length - 1);
                        })+'</td></tr>');
                    } else {
                        rows.push('<tr><th>'+i18n.t('launchdaemons.'+prop)+'</th><td>'+d[prop]+'</td></tr>');
                    }
                }
            }
            $launchdaemonsTab.append(
                $('<h4>').append($('<i>').addClass('fa fa-paper-plane')).append(' '+d.label),
                $('<div>').append(
                    $('<table>').addClass('table table-striped table-condensed').append(
                        $('<tbody>').append(rows.join(''))
                    )
                )
            );
        });
    });
});
</script>
