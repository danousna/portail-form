<?php
class Xml
{
    public static function parseCasReturn($data)
    {
        $XmlParsed = simplexml_load_string($data, "SimpleXMLElement", 0, "cas", true);
        $data = Array();
        try {
            //$user = $XmlParsed->authenticationSuccess->user;
            $data['user'] = (string) $XmlParsed->authenticationSuccess->user;
            $data['mail'] = (string) $XmlParsed->authenticationSuccess->attributes->mail;
            $data['nom'] = (string) $XmlParsed->authenticationSuccess->attributes->sn;
            $data['prenom'] = (string) $XmlParsed->authenticationSuccess->attributes->givenName;
        }
        catch (Exception $e) {
            return $e;
        }

        return $data;
    }
}
