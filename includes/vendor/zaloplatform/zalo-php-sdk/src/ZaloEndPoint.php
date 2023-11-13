<?php
/**
 * Zalo © 2019
 *
 */

namespace Zalo;

/**
 * Class ZaloConfig
 *
 * @package Zalo
 */
class ZaloEndPoint
{

    /**
     * @const
     */
    const API_GRAPH_ME = 'https://graph.zaloapp.com/v2.0/me';

    /* --------------------------------------------------------------------------------------------------- */
    /**
     * @const
     */
    const API_OA_SEND_MESSAGE = 'https://openapi.zalo.me/v2.0/oa/message';

    /**
     * @const
     */
    const API_OA_GET_LIST_TAG = 'https://openapi.zalo.me/v2.0/oa/tag/gettagsofoa';

    /**
     * @const
     */
    const API_OA_REMOVE_TAG = 'https://openapi.zalo.me/v2.0/oa/tag/rmtag';

    /**
     * @const
     */
    const API_OA_REMOVE_USER_FROM_TAG = 'https://openapi.zalo.me/v2.0/oa/tag/rmfollowerfromtag';

    /**
     * @const
     */
    const API_OA_TAG_USER = 'https://openapi.zalo.me/v2.0/oa/tag/tagfollower';

    /**
     * @const
     */
    const API_OA_UPLOAD_PHOTO = 'https://openapi.zalo.me/v2.0/oa/upload/image';

    /**
     * @const
     */
    const API_OA_UPLOAD_GIF = 'https://openapi.zalo.me/v2.0/oa/upload/gif';

    /**
     * @const
     */
    const API_OA_UPLOAD_FILE = 'https://openapi.zalo.me/v2.0/oa/upload/file';

    /**
     * @const
     */
    const API_OA_GET_USER_PROFILE = 'https://openapi.zalo.me/v2.0/oa/getprofile';

    /**
     * @const
     */
    const API_OA_GET_PROFILE = 'https://openapi.zalo.me/v2.0/oa/getoa';

    /**
     * @const
     */
    const API_OA_GET_LIST_FOLLOWER = 'https://openapi.zalo.me/v2.0/oa/getfollowers';

    /**
     * @const
     */
    const API_OA_GET_LIST_RECENT_CHAT = 'https://openapi.zalo.me/v2.0/oa/listrecentchat';

    /**
     * @const
     */
    const API_OA_GET_CONVERSATION = 'https://openapi.zalo.me/v2.0/oa/conversation';

    /**
     * @const
     */
    const API_OA_SEND_CONSULTATION_MESSAGE_V3 = 'https://openapi.zalo.me/v3.0/oa/message/cs';

    /**
     * @const
     */
    const API_OA_SEND_TRANSACTION_MESSAGE_V3 = 'https://openapi.zalo.me/v3.0/oa/message/transaction';

    /**
     * @const
     */
    const API_OA_SEND_PROMOTION_MESSAGE_V3 = 'https://openapi.zalo.me/v3.0/oa/message/promotion';
}
