<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo\Builder;

use Zalo\Exceptions\ZaloSDKException;

/**
 * Class MessageBuilder
 *
 * @package Zalo
 */
class MessageBuilder
{
    /**
     * @const string recipient
     */
    const RECIPIENT = 'recipient';
    /**
     * @const string text
     */
    const TEXT = 'text';
    /**
     * @const string attachment_id
     */
    const ATTACHMENT_ID = 'attachment_id';
    /**
     * @const string template_id
     */
    const TEMPLATE_ID = 'template_id';
    /**
     * @const string template_data
     */
    const TEMPLATE_DATA = 'template_data';
    /**
     * @const string token
     */
    const FILE_TOKEN = 'token';
    /**
     * @const string media_type
     */
    const MEDIA_TYPE = 'media_type';
    /**
     * @const string template_type
     */
    const TEMPLATE_TYPE = 'template_type';
    /**
     * @const string media_height
     */
    const MEDIA_HEIGHT = 'media_height';
    /**
     * @const string media_width
     */
    const MEDIA_WIDTH = 'media_width';
    /**
     * @const string element
     */
    const ELEMENT = 'element_';
    /**
     * @const string button
     */
    const BUTTON = 'button_';
    /**
     * @const string msg type text
     */
    const MSG_TYPE_TXT = 'text';
    /**
     * @const string msg type media
     */
    const MSG_TYPE_MEDIA = 'media';
    /**
     * @const string msg type list
     */
    const MSG_TYPE_LIST = 'list';
    /**
     * @const string msg type file
     */
    const MSG_TYPE_FILE = 'file';
    /**
     * @const string msg type template
     */
    const MSG_TYPE_TEMPLATE = 'template';
    /**
     * @const string msg type transaction
     */
    const MSG_TYPE_TRANSACTION = 'transaction';
    /**
     * @const string msg type promotion
     */
    const MSG_TYPE_PROMOTION = 'promotion';
    /**
     * @const string msg type request user info
     */
    const MSG_TYPE_REQUEST_USER_INFO = 'request_user_info';
    /**
     * @const string max element
     */
    const MAX_ELEMENT = 5;
    /**
     * @const string The media url.
     */
    const MEDIA_URL = 'media_url';
    /**
     * @const string The quote message id.
     */
    const QUOTE_MESSAGE_ID = 'quote_message_id';
    /**
     * @var string The message type.
     */
    protected $type;
    /**
     * @var string The element index.
     */
    protected $elementIndex;
    /**
     * @var string The app button index.
     */
    protected $buttonIndex;
    /**
     * @var string The params data.
     */
    protected $data;
    /**
     * @var string The message language.
     *
     */
    protected $language;

    /**
     * The constructor
     *
     * @param string $msgTyoe
     *
     * @throws ZaloSDKException
     */
    public function __construct($msgType)
    {
        if (!is_string($msgType)) {
            if (static::MSG_TYPE_TXT !== $msgType || static::MSG_TYPE_MEDIA !== $msgType || static::MSG_TYPE_LIST !== $msgType ||
                static::MSG_TYPE_FILE !== $msgType || static::MSG_TYPE_TEMPLATE !== $msgType || static::MSG_TYPE_TRANSACTION != $msgType ||
                static::MSG_TYPE_PROMOTION !== $msgType || static::MSG_TYPE_REQUEST_USER_INFO !== $msgType) {
                throw new ZaloSDKException('only support 8 message type text, media, list, template, file, transaction, promotion, request_user_info');
            }
            throw new ZaloSDKException('message type is empty! only support 8 message type text, media, list, template, file, transaction, promotion, request_user_info');
        }
        $this->elementIndex = 0;
        $this->buttonIndex = 0;
        $this->data = array(
            static::TEXT => "",
            static::QUOTE_MESSAGE_ID => "",
            static::MEDIA_TYPE => "image",
            static::TEMPLATE_TYPE => "invite",
            static::MEDIA_HEIGHT => 280,
            static::MEDIA_WIDTH => 500,
        );
        $this->type = $msgType;
    }

    /**
     * Set media type
     *
     * @param string $type
     *
     * @throws ZaloSDKException
     */
    public function withMediaType($type)
    {
        if ('image' !== $type && 'gif' !== $type && 'sticker' !== $type) {
            throw new ZaloSDKException('media type only support type image, gif, sticker');
        }
        $this->data[static::MEDIA_TYPE] = $type;
    }

    /**
     * Set template type
     *
     * @param string $type
     *
     * @throws ZaloSDKException
     */
    public function withTemplateType($type)
    {
        $this->data[static::TEMPLATE_TYPE] = $type;
    }

    /**
     * Set media size
     *
     * @param string $width
     * @param string $height
     *
     */
    public function withMediaSize($height, $width)
    {
        $this->data[static::MEDIA_HEIGHT] = $height;
        $this->data[static::MEDIA_WIDTH] = $width;
    }

    /**
     * Set user_id
     *
     * @param string $id
     *
     */
    public function withUserId($id)
    {
        $recipient = array(
            'user_id' => $id
        );
        $this->data[static::RECIPIENT] = $recipient;
    }

    /**
     * Set phone number
     *
     * @param string $phone
     *
     */
    public function withPhoneNumber($phone)
    {
        $recipient = array(
            'phone' => $phone
        );
        $this->data[static::RECIPIENT] = $recipient;
    }

    /**
     * Set message id
     *
     * @param string $mid
     *
     */
    public function withMessageId($mid)
    {
        $recipient = array(
            'message_id' => $mid
        );
        $this->data[static::RECIPIENT] = $recipient;
    }

    /**
     * Set group id
     *
     * @param string $gid
     *
     */
    public function withGroupId($gid)
    {
        $recipient = array(
            'group_id' => $gid
        );
        $this->data[static::RECIPIENT] = $recipient;
    }

    /**
     * Set anonymous conversation
     */
    public function withAnonymousConversation($anonymousId, $conversationId)
    {
        $recipient = array(
            'anonymous_id' => $anonymousId,
            'conversation_id' => $conversationId
        );
        $this->data[static::RECIPIENT] = $recipient;
    }

    /**
     * Set text
     *
     * @param string $content
     *
     */
    public function withText($content)
    {
        $this->data[static::TEXT] = $content;
    }

    /**
     * Set quote message id
     *
     * @param $messageId
     */
    public function withQuoteMessage($messageId)
    {
        $this->data[static::QUOTE_MESSAGE_ID] = $messageId;
    }

    /**
     * Set file token
     *
     * @param string $token
     *
     */
    public function withFileToken($token)
    {
        $this->data[static::FILE_TOKEN] = $token;
    }

    /**
     * Set attachment id
     *
     * @param string $attachmentId
     *
     */
    public function withAttachment($attachmentId)
    {
        $this->data[static::ATTACHMENT_ID] = $attachmentId;
    }

    /**
     * Set media url
     */
    public function withMediaUrl($url)
    {
        $this->data[static::MEDIA_URL] = $url;
    }

    /**
     * Set template & template data
     *
     * @param string $templateId
     * @param string $data
     */
    public function withTemplate($templateId, $data)
    {
        $this->data[static::TEMPLATE_ID] = $templateId;
        $this->data[static::TEMPLATE_DATA] = $data;
    }

    /**
     * Build action open url
     *
     * @param string $url
     *
     */
    public function buildActionOpenURL($url)
    {
        $action = array(
            'type' => 'oa.open.url',
            'payload' => array(
                'url' => $url
            ),
            'url' => $url
        );
        return $action;
    }

    /**
     * Build action query show
     *
     * @param string $callbackData
     *
     */
    public function buildActionQueryShow($callbackData)
    {
        $action = array(
            'type' => 'oa.query.show',
            'payload' => $callbackData
        );
        return $action;
    }

    /**
     * Build action query hide
     *
     * @param string $callbackData
     *
     */
    public function buildActionQueryHide($callbackData)
    {
        $action = array(
            'type' => 'oa.query.hide',
            'payload' => $callbackData
        );
        return $action;
    }

    /**
     * Build action open phone
     *
     * @param string $phoneNumber
     *
     */
    public function buildActionOpenPhone($phoneNumber)
    {
        $action = array(
            'type' => 'oa.open.phone',
            'payload' => array(
                'phone_code' => $phoneNumber
            )
        );
        return $action;
    }

    /**
     * Build action open SMS
     *
     * @param string $phoneNumber
     * @param string $smsText
     */
    public function buildActionOpenSMS($phoneNumber, $smsText)
    {
        $action = array(
            'type' => 'oa.open.sms',
            'payload' => array(
                'phone_code' => $phoneNumber,
                'content' => $smsText
            )
        );
        return $action;
    }

    /**
     * Add message element
     *
     * @param string $title
     * @param string $thumb
     * @param string $description
     * @param string $action
     *
     * @deprecated withElement does not support for Official Account API V3. Please use addElement function.
     */
    public function withElement($title, $thumb, $description, $action)
    {
        if ($this->elementIndex >= static::MAX_ELEMENT) {
            throw new ZaloSDKException('Only support 4 elements');
        }
        $element = array(
            'title' => $title,
            'image_url' => $thumb,
            'subtitle' => $description,
            'default_action' => $action
        );
        $this->data[static::ELEMENT . $this->elementIndex] = $element;
        $this->elementIndex++;
    }

    /**
     * Add message element
     *
     * @param string $element
     */
    public function addElement($element)
    {
        $this->data[static::ELEMENT . $this->elementIndex] = $element;
        $this->elementIndex++;
    }

    /**
     * Add message button
     *
     * @param string $title
     * @param string $action
     *
     * @deprecated withButton does not support for Official Account API V3. Please use addButton function.
     */
    public function withButton($title, $action)
    {
        if ($this->buttonIndex >= static::MAX_ELEMENT) {
            throw new ZaloSDKException('Only support 4 buttons');
        }
        $button = array(
            'title' => $title,
            'type' => $action['type'],
            'payload' => $action['payload']
        );
        $this->data[static::BUTTON . $this->buttonIndex] = $button;
        $this->buttonIndex++;
    }

    /**
     * Add message button
     *
     * @param string $title
     * @param string $imageIcon
     * @param string $action
     *
     */
    public function addButton($title, $imageIcon, $action)
    {
        $button = array(
            'title' => $title,
            'type' => $action['type'],
            'image_icon' => $imageIcon,
            'payload' => $action['payload']
        );
        $this->data[static::BUTTON . $this->buttonIndex] = $button;
        $this->buttonIndex++;
    }

    /**
     * Build buttons
     */
    private function buildButtons()
    {
        $buttons = array();
        for ($i = 0; $i < $this->buttonIndex; $i++) {
            array_push($buttons, $this->data[static::BUTTON . $i]);
        }
        return $buttons;
    }

    /**
     * Build elements
     */
    private function buildElements()
    {
        $elements = array();
        for ($i = 0; $i < $this->elementIndex; $i++) {
            array_push($elements, $this->data[static::ELEMENT . $i]);
        }
        return $elements;
    }

    /**
     * Set language message
     */
    public function withLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Build message
     */
    public function build()
    {
        switch ($this->type) {
            case 'text':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'text' => $this->data[static::TEXT],
                        'quote_message_id' => $this->data[static::QUOTE_MESSAGE_ID]
                    )
                );
                return $message;
            case 'media':
                $element = array(
                    'media_type' => $this->data[static::MEDIA_TYPE],
                    'width' => $this->data[static::MEDIA_WIDTH],
                    'height' => $this->data[static::MEDIA_HEIGHT]
                );
                if (isset($this->data[static::ATTACHMENT_ID])) {
                    $element['attachment_id'] = $this->data[static::ATTACHMENT_ID];
                } else {
                    $element['url'] = $this->data[static::MEDIA_URL];
                }
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'text' => $this->data[static::TEXT],
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'media',
                                'elements' => array(
                                    $element
                                ),
                            )
                        )
                    )
                );
                return $message;
            case 'list':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'list',
                                'elements' => $this->buildElements(),
                                'buttons' => $this->buildButtons()
                            )
                        )
                    )
                );
                return $message;
            case 'file':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'file',
                            'payload' => array(
                                'token' => $this->data[static::FILE_TOKEN],
                            )
                        )
                    )
                );
                return $message;
            case 'template':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => $this->data[static::TEMPLATE_TYPE],
                                'elements' => array(
                                    array(
                                        'template_id' => $this->data[static::TEMPLATE_ID],
                                        'template_data' => $this->data[static::TEMPLATE_DATA]
                                    )
                                )
                            )
                        )
                    )
                );
                return $message;
            case 'transaction':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => $this->data[static::TEMPLATE_TYPE],
                                'language' => $this->language,
                                'elements' => $this->buildElements(),
                                'buttons' => $this->buildButtons()
                            )
                        )
                    )
                );
                return $message;
            case 'promotion':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'promotion',
                                'elements' => $this->buildElements(),
                                'buttons' => $this->buildButtons()
                            )
                        )
                    )
                );
                return $message;
            case 'request_user_info':
                $message = array(
                    'recipient' => $this->data[static::RECIPIENT],
                    'message' => array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'request_user_info',
                                'elements' => $this->buildElements(),
                            )
                        )
                    )
                );
                return $message;
            default:
                break;
        }
        return array();
    }
}
