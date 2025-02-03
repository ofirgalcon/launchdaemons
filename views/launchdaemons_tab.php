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
</div><h2 data-i18n="launchdaemons.launchdaemons"></h2>

<script>
$(document).on('appReady', function(){
    // Cache common selectors
    const $launchdaemonsTab = $('#launchdaemons-tab');
    const $launchdaemonsCnt = $('#launchdaemons-cnt');
    
    // Properties to skip in output
    const skipThese = ['id', 'serial_number', 'label'];
    
    // Boolean properties that need yes/no translation
    const booleanProps = ['disabled', 'ondemand', 'runatload', 'startonmount', 'keepalive'];
    
    $.getJSON(appUrl + '/module/launchdaemons/get_tab_data/' + serialNumber, function(data){
        // Store data globally for modal access
        window.launchdaemonsData = data;
        
        // Update count
        $launchdaemonsCnt.text(data.length);
        
        // Build HTML output
        const chunks = [];
        for (let i = 0, len = data.length; i < len; i++) {
            const d = data[i];
            
            // Add record header
            chunks.push(`<h4><i class="fa fa-paper-plane"></i> ${d.label}</h4><ul class="list-group">`);
            
            // Add record properties
            for (const prop in d) {
                // Skip certain properties and empty values
                if (skipThese.includes(prop)) continue;
                if ((d[prop] === '' || d[prop] === null) && d[prop] !== "0") continue;
                
                // Start list item
                let item = `<li class="list-group-item"><strong>${i18n.t('launchdaemons.' + prop)}:</strong> `;
                
                // Add property value based on type
                if (prop === "startinterval" && +d[prop] >= 60) {
                    const duration = moment.duration(+d[prop], "seconds").humanize();
                    item += `<span title="${d[prop]} ${i18n.t('launchdaemons.seconds')}">${duration}</span>`;
                }
                else if (booleanProps.includes(prop)) {
                    const boolVal = (d[prop] == 1 || d[prop] === true);
                    item += i18n.t(boolVal ? 'yes' : 'no');
                }
                else if (prop === 'daemon_json') {
                    item += `<button type="button" class="btn btn-info btn-xs view-daemon" data-index="${i}">${i18n.t('launchdaemons.view_button')}</button>`;
                }
                else {
                    item += d[prop];
                }
                
                chunks.push(item + '</li>');
            }
            chunks.push('</ul>');
        }
        
        // Update DOM once with complete HTML
        $launchdaemonsTab.html(chunks.join(''));
    });
});

// Handle View button clicks
$(document).on('click', '.view-daemon', function(e){
    e.preventDefault();
    
    const index = parseInt($(this).attr('data-index'), 10);
    const record = window.launchdaemonsData[index];
    
    if (record?.daemon_json) {
        // Cache modal elements
        const $modal = $('#myModal');
        const $dialog = $modal.find('.modal-dialog');
        const $title = $modal.find('.modal-title');
        const $body = $modal.find('.modal-body');
        const $okBtn = $modal.find('button.ok');
        
        // Configure modal
        $dialog.addClass('modal-lg');
        $title.empty().append(record.label);
        $body.empty().append(record.daemon_json.replace(/\n/g, '<br>'));
        
        // Configure close button
        $okBtn
            .text(i18n.t("dialog.close"))
            .off()
            .click(() => $modal.modal('hide'));
        
        // Show modal
        $modal.modal('show');
    }
    
    return false;
});
</script>
