<?php
    require_once 'BasicEnum.php';
    abstract class DeviceType extends BasicEnum {
        const L = "LIGHT";
        const D_L = "DIMMING_LIGHT";
        const C_L = "COLORED_LIGHT";
        const S = "SWITCH";
        const T = "THERMOSTAT";
    }