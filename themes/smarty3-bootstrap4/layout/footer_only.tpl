        {if isset($chromeframe) && $chromeframe eq '1'}
            <p class="chromeframe">{$LANG.chromeframe}</p>
        {/if}
        <div id="timeoutsess" class="chromeframe">
            {$LANG.timeoutsess_nouser}, <a onclick="timeoutsesscancel();" href="#">{$LANG.timeoutsess_click}</a>. {$LANG.timeoutsess_timeout}: <span id="secField"> 60 </span> {$LANG.sec}
        </div>
        <script src="/themes/smarty3-bootstrap4/assets/js/plugins.min.js"></script>
        <script src="/themes/smarty3-bootstrap4/assets/js/app.min.js"></script>
	</body>
</html>