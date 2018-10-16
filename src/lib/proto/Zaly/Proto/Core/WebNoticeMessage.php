<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: core/message.proto

namespace Zaly\Proto\Core;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>core.WebNoticeMessage</code>
 */
class WebNoticeMessage extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string code = 1;</code>
     */
    private $code = '';
    /**
     * Generated from protobuf field <code>int32 height = 2;</code>
     */
    private $height = 0;
    /**
     * string hrefURL  = 3;
     *
     * Generated from protobuf field <code>string title = 4;</code>
     */
    private $title = '';
    /**
     * Generated from protobuf field <code>int32 pluginId = 5;</code>
     */
    private $pluginId = 0;
    /**
     * Generated from protobuf field <code>.core.SimplePluginProfile jumpPluginProfile = 7;</code>
     */
    private $jumpPluginProfile = null;

    public function __construct() {
        \GPBMetadata\Core\Message::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 height = 2;</code>
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Generated from protobuf field <code>int32 height = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setHeight($var)
    {
        GPBUtil::checkInt32($var);
        $this->height = $var;

        return $this;
    }

    /**
     * string hrefURL  = 3;
     *
     * Generated from protobuf field <code>string title = 4;</code>
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * string hrefURL  = 3;
     *
     * Generated from protobuf field <code>string title = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setTitle($var)
    {
        GPBUtil::checkString($var, True);
        $this->title = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int32 pluginId = 5;</code>
     * @return int
     */
    public function getPluginId()
    {
        return $this->pluginId;
    }

    /**
     * Generated from protobuf field <code>int32 pluginId = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setPluginId($var)
    {
        GPBUtil::checkInt32($var);
        $this->pluginId = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.core.SimplePluginProfile jumpPluginProfile = 7;</code>
     * @return \Zaly\Proto\Core\SimplePluginProfile
     */
    public function getJumpPluginProfile()
    {
        return $this->jumpPluginProfile;
    }

    /**
     * Generated from protobuf field <code>.core.SimplePluginProfile jumpPluginProfile = 7;</code>
     * @param \Zaly\Proto\Core\SimplePluginProfile $var
     * @return $this
     */
    public function setJumpPluginProfile($var)
    {
        GPBUtil::checkMessage($var, \Zaly\Proto\Core\SimplePluginProfile::class);
        $this->jumpPluginProfile = $var;

        return $this;
    }

}

