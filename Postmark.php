<?php

/**
 * Created by PhpStorm.
 * User: Minalo Tri
 * Date: 9/7/2016
 * Time: 3:23 AM
 */
class Postmark
{
    private static $endPoint;
    private static $senderSignature;
    private static $emailSubject;
    private static $emailBody;
    private static $serverToken;
    private static $attachment;
    private static $emailTag;
    private static $trackingEmail;
    private static $emailReciver;
    private static $replyTo;

    /**
     * Digunakan untuk melakukan setting parameter seperti signature / secret key
     *
     * @param $param
     */
    public function config($param)
    {
        Postmark::$endPoint         = 'https://api.postmarkapp.com/email';
        Postmark::$senderSignature  = $param['senderSignature'];
        Postmark::$serverToken      = $param['serverToken'];
        Postmark::$trackingEmail    = $param['trackingEmail'];
    }

    /**
     * Digunakan untuk mengisi penerima email
     *
     * @param $emailReciver
     */
    public function emailReciver($emailReciver)
    {
        Postmark::$emailReciver = $emailReciver;
    }

    public function replyTo($replyTo)
    {
        Postmark::$replyTo = $replyTo;
    }

    /**
     * Digunakan untuk mengisi subject email yang akan dikirim
     *
     * @param $emailSubject
     */
    public function emailSubject($emailSubject)
    {
        Postmark::$emailSubject = $emailSubject;
    }

    /**
     * Digunakan untuk mengisi body email
     *
     * @param $emailBody
     */
    public function emailBody($emailBody)
    {
        Postmark::$emailBody = $emailBody;
    }

    /**
     * Digunakan untuk melakukan embed attachment pada email
     *
     * Extensi yang diizinkan adalah
     * 1. JPEG
     * 2. JPG
     * 3. PNG
     * 4. PDF
     *
     * @param $attachmentLocation use for located attachment
     * @param $attachmentName use for rename file attachment to spesific name
     * @param $attachmentType use for choose content type
     */
    public function embedAttachment($attachmentLocation, $attachmentName, $attachmentType)
    {
        $dataAttachment = array(
            'Name'          => $attachmentName.".".$attachmentType,
            'Content'       => base64_encode(file_get_contents($attachmentLocation)),
            'ContentType'  => $this->_searchContentType($attachmentType)
        );
        Postmark::$attachment = $dataAttachment;
    }

    /**
     * Digunakan untuk melakukan tag pada email
     *
     * @param $emailTag
     */
    public function emailTag($emailTag)
    {
        Postmark::$emailTag = $emailTag;
    }

    /**
     * Digunakan untuk melakukan pengiriman email
     *
     * @return mixed
     */
    public function sendEmail()
    {
        $curlInit = curl_init();
        $curlOptions = array(
            CURLOPT_URL           => Postmark::$endPoint,
            CURLOPT_HTTPHEADER    => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'X-Postmark-Server-Token: '.Postmark::$serverToken
            ),
            CURLOPT_HTTPAUTH            => 1,
            CURLOPT_RETURNTRANSFER      => 1,
            CURLOPT_POST                => 1,
            CURLOPT_POSTFIELDS          => $this->_singleEmail()
        );
        curl_setopt_array($curlInit, $curlOptions);
        $result = curl_exec($curlInit);
        return $result;
    }

    /**
     * Digunakan untuk membuat format data json
     *
     * @return string
     */
    private function _singleEmail()
    {
        $data = array(
            'From'      => Postmark::$senderSignature,
            'To'        => Postmark::$emailReciver,
            'Subject'   => Postmark::$emailSubject,
            'Tag'       => (Postmark::$emailTag != '' ? Postmark::$emailTag : ''),
            'HtmlBody'  => Postmark::$emailBody,
            'Attachment'=> array(Postmark::$attachment),
            'ReplyTo'   => Postmark::$replyTo,
            'TrackOpens'=> Postmark::$trackingEmail
        );

        return json_encode($data);
    }

    private function _batchEmail()
    {

    }

    /**
     * Digunakan untuk memilih content type sesuai dengan extension yang diberikan
     *
     * @param $contentExtension
     */
    private function _searchContentType($contentExtension)
    {
        $contentType = '';

        switch ($contentExtension)
        {
            case 'pdf':
                $contentType = 'application/pdf';
                break;
            case 'jpg';
            case 'jpeg':
                $contentType = 'image/jpg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            default:
                break;
        }

        return $contentType;
    }
}
