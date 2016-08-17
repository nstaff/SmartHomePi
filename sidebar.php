<div class="sidebar col-xs-4">
    <ul>
        <li>
            <a href="/smarthome/index.php" id="home-link">
                <i class="fa fa-home"></i>
                Home
            </a>
        </li>
        <li>
            <a href="/smarthome/installation.php" id="installation-link">
                <i class="fa fa-plus-square"></i>
                Installation
            </a>
        </li>
        <li>
            <a href="/smarthome/viewDevices.php" id="devices-link">
                <i class="fa fa-tablet"></i>
                Devices
            </a>
        </li>
        <li>
            <a href="/smarthome/setup.php" id="setup-link">
                <i class="fa fa-cog"></i>
                Setup
            </a>
        </li>
    </ul>
</div>

<script type="text/javascript">

    function setSelectedLink() {
        var link;
        var pathName = window.location.pathname;
        if (pathName.indexOf('index') > -1) {
            link = document.getElementById('home-link');
        } else if (pathName.indexOf('installation') > -1) {
            link = document.getElementById('installation-link');

        } else if (pathName.indexOf('Devices') > -1) {
            link = document.getElementById('devices-link');

        } else if(pathName.indexOf('setup') > -1) {
            link = document.getElementById('setup-link');

        }
        
        link.className = 'selected';
    }

    setSelectedLink();
</script>