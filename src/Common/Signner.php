<?php

namespace NFePHP\NFSe\Common;

/**
 * Classe para a assinar um Xml
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Common\Signner
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Certificate;
use DOMDocument;
use DOMElement;
use RuntimeException;

class Signner
{
    /**
     * sign
     * @param string $content
     * @param string $tagid
     * @param string $marcador
     * @param string $algorithm
     * @return string xml assinado
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     */
    public static function sign(
        Certificate $certificate,
        $content,
        $tagid = '',
        $mark = 'Id',
        $algorithm = OPENSSL_ALGO_SHA1
    ) {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($content);
        $root = $dom->documentElement;
        $node = $dom->getElementsByTagName($tagid)->item(0);
        if (!isset($node)) {
            throw new RuntimeException("A tag < $tagid > não existe no XML!!");
        }
        if (! self::signatureExists($dom)) {
            $xml = self::createSignature(
               $certificate,
               $dom,
               $root,
               $node,
               $mark,
               $algorithm
            );
        }
        return $xml;
    }

    /**
     * createSignature
     * Método que provê a assinatura do xml conforme padrão SEFAZ
     * @param \DOMDocument $xmldoc
     * @param \DOMElement $root
     * @param \DOMElement $node
     * @param string $marcador
     * @param string $algorithm
     * @return string xml assinado
     * @internal param DOMDocument $xmlDoc
     */
    private static function createSignature(
        Certificate $certificate,
        DOMDocument $dom,
        DOMElement $root,
        DOMElement $node,
        $mark,
        $algorithm = OPENSSL_ALGO_SHA1
    ) {
        $nsDSIG = 'http://www.w3.org/2000/09/xmldsig#';
        $nsCannonMethod = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        
        $nsSignatureMethod = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
        $nsDigestMethod = 'http://www.w3.org/2000/09/xmldsig#sha1';
        $digestAlgorithm = 'sha1';
        if ($algorithm == OPENSSL_ALGO_SHA256) {
            $digestAlgorithm = 'sha256';
            $nsSignatureMethod = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';
            $nsDigestMethod = 'http://www.w3.org/2001/04/xmlenc#sha256';
        }
        $nsTransformMethod1 ='http://www.w3.org/2000/09/xmldsig#enveloped-signature';
        $nsTransformMethod2 = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
        $idSigned = trim($node->getAttribute($mark));
        $digestValue = self::calculeDigest($node, $digestAlgorithm);
        //cria o node <Signature>
        $signatureNode = $dom->createElementNS($nsDSIG, 'Signature');
        //adiciona a tag <Signature> ao node raiz
        $root->appendChild($signatureNode);
        //cria o node <SignedInfo>
        $signedInfoNode = $dom->createElement('SignedInfo');
        //adiciona o node <SignedInfo> ao <Signature>
        $signatureNode->appendChild($signedInfoNode);
        //cria no node com o método de canonização dos dados
        $canonicalNode = $dom->createElement('CanonicalizationMethod');
        //adiona o <CanonicalizationMethod> ao node <SignedInfo>
        $signedInfoNode->appendChild($canonicalNode);
        //seta o atributo ao node <CanonicalizationMethod>
        $canonicalNode->setAttribute('Algorithm', $nsCannonMethod);
        //cria o node <SignatureMethod>
        $signatureMethodNode = $dom->createElement('SignatureMethod');
        //adiciona o node <SignatureMethod> ao node <SignedInfo>
        $signedInfoNode->appendChild($signatureMethodNode);
        //seta o atributo Algorithm ao node <SignatureMethod>
        $signatureMethodNode->setAttribute('Algorithm', $nsSignatureMethod);
        //cria o node <Reference>
        $referenceNode = $dom->createElement('Reference');
        //adiciona o node <Reference> ao node <SignedInfo>
        $signedInfoNode->appendChild($referenceNode);
        //seta o atributo URI a node <Reference>
        if (!empty($idSigned)) {
            $idSigned = "#$idSigned";
        }
        $referenceNode->setAttribute('URI', $idSigned);
        //cria o node <Transforms>
        $transformsNode = $dom->createElement('Transforms');
        //adiciona o node <Transforms> ao node <Reference>
        $referenceNode->appendChild($transformsNode);
        //cria o primeiro node <Transform> OBS: no singular
        $transfNode1 = $dom->createElement('Transform');
        //adiciona o primeiro node <Transform> ao node <Transforms>
        $transformsNode->appendChild($transfNode1);
        //set o atributo Algorithm ao primeiro node <Transform>
        $transfNode1->setAttribute('Algorithm', $nsTransformMethod1);
        //cria outro node <Transform> OBS: no singular
        $transfNode2 = $dom->createElement('Transform');
        //adiciona o segundo node <Transform> ao node <Transforms>
        $transformsNode->appendChild($transfNode2);
        //set o atributo Algorithm ao segundo node <Transform>
        $transfNode2->setAttribute('Algorithm', $nsTransformMethod2);
        //cria o node <DigestMethod>
        $digestMethodNode = $dom->createElement('DigestMethod');
        //adiciona o node <DigestMethod> ao node <Reference>
        $referenceNode->appendChild($digestMethodNode);
        //seta o atributo Algorithm ao node <DigestMethod>
        $digestMethodNode->setAttribute('Algorithm', $nsDigestMethod);
        //cria o node <DigestValue>
        $digestValueNode = $dom->createElement('DigestValue', $digestValue);
        //adiciona o node <DigestValue> ao node <Reference>
        $referenceNode->appendChild($digestValueNode);
        //extrai node <SignedInfo> para uma string na sua forma canonica
        $content = $signedInfoNode->C14N(true, false, null, null);
        //cria uma variavel vazia que receberá a assinatura
        $signature = $certificate->sign($content, $algorithm);
        //converte a assinatura em base64
        $signatureValue = base64_encode($signature);
        //cria o node <SignatureValue>
        $signatureValueNode = $dom->createElement('SignatureValue', $signatureValue);
        //adiciona o node <SignatureValue> ao node <Signature>
        $signatureNode->appendChild($signatureValueNode);
        //cria o node <KeyInfo>
        $keyInfoNode = $dom->createElement('KeyInfo');
        //adiciona o node <KeyInfo> ao node <Signature>
        $signatureNode->appendChild($keyInfoNode);
        //cria o node <X509Data>
        $x509DataNode = $dom->createElement('X509Data');
        //adiciona o node <X509Data> ao node <KeyInfo>
        $keyInfoNode->appendChild($x509DataNode);
        //remove linhas desnecessárias do certificado
        $pubKeyClean = $certificate->publicKey->unFormated();
        //cria o node <X509Certificate>
        $x509CertificateNode = $dom->createElement('X509Certificate', $pubKeyClean);
        //adiciona o node <X509Certificate> ao node <X509Data>
        $x509DataNode->appendChild($x509CertificateNode);
        //salva o xml completo em uma string
        return $dom->saveXML();
    }
    
    /**
     * verifySignature
     * Verifica a validade da assinatura digital contida no xml
     * @param string $content conteudo do xml a ser verificado ou o path completo
     * @param string $tagid tag que foi assinada no documento xml
     * @return boolean
     */
    public static function verifySignature($content, $tagid)
    {
        if (is_file($content)) {
            $content = file_get_contents($content);
        }
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($content);
        $flag = self::signatureExists($dom);
        $flag &= self::digestCheck($dom, $tagid);
        $flag &= self::signatureCheck($dom);
        return $flag;
    }

    /**
     * signatureExists
     * Check se o xml possi a tag Signature
     * @param \DOMDocument $dom
     * @return boolean
     */
    private static function signatureExists(DOMDocument $dom)
    {
        $signature = $dom->getElementsByTagName('Signature')->item(0);
        if (! isset($signature)) {
            return false;
        }
        return true;
    }
    
    /**
     * signatureCheck
     * @param \DOMDocument $dom
     * @return boolean
     * @throws Exception\RuntimeException
     */
    private static function signatureCheck(DOMDocument $dom)
    {
        $sigMethAlgo = $dom->getElementsByTagName('SignatureMethod')->item(0)->getAttribute('Algorithm');
        if ($sigMethAlgo == 'http://www.w3.org/2000/09/xmldsig#rsa-sha1') {
            $signAlgorithm = OPENSSL_ALGO_SHA1;
        } else {
            $signAlgorithm = OPENSSL_ALGO_SHA256;
        }
        $x509Certificate = $dom->getElementsByTagName('X509Certificate')->item(0)->nodeValue;
        $x509Certificate =  "-----BEGIN CERTIFICATE-----\n"
            . self::splitLines($x509Certificate)
            . "\n-----END CERTIFICATE-----\n";
        $objSSLPubKey = openssl_pkey_get_public($x509Certificate);
        if ($objSSLPubKey === false) {
            $msg = "Ocorreram problemas ao carregar a chave pública. Certificado incorreto ou corrompido!!";
            $this->thowOpenSSLError($msg);
        }
        $signContent = $dom->getElementsByTagName('SignedInfo')->item(0)->C14N(true, false, null, null);
        $signatureValue = $dom->getElementsByTagName('SignatureValue')->item(0)->nodeValue;
        $decodedSignature = base64_decode(str_replace(array("\r", "\n"), '', $signatureValue));
        $resp = openssl_verify($signContent, $decodedSignature, $objSSLPubKey, $signAlgorithm);
        if ($resp != 1) {
            $msg = "Problema ({$resp}) ao verificar a assinatura do digital!!";
            $this->thowOpenSSLError($msg);
        }
        return true;
    }
    
    /**
     * digestCheck
     * @param DOMDocument $dom
     * @param string $tagid
     * @return boolean
     * @throws Exception\RuntimeException
     */
    private static function digestCheck(DOMDocument $dom, $tagid = '')
    {
        $node = $dom->getElementsByTagName($tagid)->item(0);
        if (empty($node)) {
            throw new RuntimeException("A tag < $tagid > não existe no XML!!");
        }
        $sigMethAlgo = $dom->getElementsByTagName('SignatureMethod')->item(0)->getAttribute('Algorithm');
        $algorithm = 'sha256';
        if ($sigMethAlgo == 'http://www.w3.org/2000/09/xmldsig#rsa-sha1') {
            $algorithm = 'sha1';
        }
        $calculatedDigest = self::calculeDigest($node, $algorithm);
        $informedDigest = $dom->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        if ($calculatedDigest != $informedDigest) {
            $msg = "O conteúdo do XML não confere com o Digest Value.\n
                Digest calculado [{$calculatedDigest}], digest informado no XML [{$informedDigest}].\n
                O arquivo pode estar corrompido ou ter sido adulterado.";
            throw new RuntimeException($msg);
        }
        return true;
    }
    
    private static function calculeDigest(DOMElement $node, $algorithm)
    {
        $tagInf = $node->C14N(true, false, null, null);
        $hashValue = hash($algorithm, $tagInf, true);
        return base64_encode($hashValue);
    }
    
    /**
     * thowOpenSSLError
     * @param string $msg
     * @return string
     */
    private static function thowOpenSSLError($msg = '')
    {
        while ($erro = openssl_error_string()) {
            $msg .= $erro . "\n";
        }
        throw new Exception\RuntimeException($msg);
    }
    
    /**
     * splitLines
     * Divide a string do certificado publico em linhas
     * com 76 caracteres (padrão original)
     * @param string $cntIn certificado
     * @return string certificado reformatado
     */
    protected static function splitLines($cntIn)
    {
        return rtrim(chunk_split(str_replace(["\r", "\n"], '', $cntIn), 76, "\n"));
    }
}
