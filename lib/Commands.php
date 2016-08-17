<?php
/**
 * These classes present a high level of coupling to database tables.
 * YOU MUST update this file if data table names/fields are changed
 */
    require_once 'BasicEnum.php';
    
    /**
     * Base "concrete" class for all commands. These are commands that are 
     * shared with all device types. Mostly structural/implementation information
     * is provided in these fields
     */
    abstract class CommonCommands extends BasicEnum{
        const INIT = "INIT";
        const VARS = "VARS";
        const NAME = "NAME";
    }
    
    /**
     * Enumeration of test specific POST values
     */
    abstract class TestCommands extends CommonCommands{
        const CMD = "CMD";
    }
    /**
     * Enumeration of Switch specific values
     */
    abstract class SwitchCommands extends CommonCommands {
        const ON_CMD = "ON_CMD";
        const OFF_CMD = "OFF_CMD";
        const GET_STATE = "GET_STATE_CMD";
    }
    /**
     * Enumeration of Light specific values
     */
    abstract class LightCommands extends CommonCommands {
        const ON_CMD = "ON_CMD";
        const OFF_CMD = "OFF_CMD";
        const GET_STATE = "GET_STATE_CMD";
        const SET_RGB = "SET_RGB";
    }
    
    abstract class DimmingLightCommands extends LightCommands {
        const SET_BRIGHTNESS = "SET_BRIGHTNESS";
    }
    
    abstract class ColoredLightCommands extends DimmingLightCommands {
        const SET_RGB = "SET_RGB";
    }
    
    /**
     * Enumeration of Thermostat specific values
     */
    abstract class ThermostatCommands extends CommonCommands {
        const SET_TEMP = "SET_TEMP";
        const GET_TEMP = "GET_TEMP";
        const FAN_ON = "FAN_ON_CMD";
        const FAN_OFF = "FAN_OFF_CMD";
        const COOL = "COOL";
        const HEAT = "HEAT";
        const AUTO = "AUTO";
    }
    
    
