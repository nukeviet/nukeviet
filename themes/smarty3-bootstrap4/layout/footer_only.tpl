        {if isset($chromeframe) && $chromeframe eq '1'}
            <p class="chromeframe">{$LANG.chromeframe}</p>
        {/if}
        <div id="timeoutsess" class="chromeframe">
            {$LANG.timeoutsess_nouser}, <a onclick="timeoutsesscancel();" href="#">{$LANG.timeoutsess_click}</a>. {$LANG.timeoutsess_timeout}: <span id="secField"> 60 </span> {$LANG.sec}
        </div>
        <script src="{$NV_BASE_TEMPLATE}/js/bootstrap.min.js"></script>
	</body>
</html>