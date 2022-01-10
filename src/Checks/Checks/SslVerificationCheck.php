<?php

namespace MediactiveDigital\MonitoringClient\Checks\Checks;

use Exception;
use MediactiveDigital\MonitoringClient\Checks\Check;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class SslVerificationCheck extends Check
{

    private $initialized = false;
    private $domain = '';

    const DEFAULT_DELAY_ALERT   = 0;
    const DEFAULT_DELAY_WARNING = 3;

    public function getName(): string
    {
        return 'SslVerification';
    }

    public function setConfiguration($configuration): Check
    {
        $this->domain = isset($configuration['domain']) ? $configuration['domain'] : "https://" . $_SERVER['HTTP_HOST'];
        $this->initialized = true;
        return $this;
    }

    public function run(): array
    {
        if ($this->initialized) {

            try{
                $original_parse = parse_url($this->domain, PHP_URL_HOST);
                
                $get = stream_context_create(array("ssl" => array(
                    "capture_peer_cert" => TRUE,
                    "verify_peer"=>false,
                    "verify_peer_name"=>false
                )));
                $read = stream_socket_client(
                    "ssl://" . $original_parse . ":443",
                    $errno,
                    $errstr,
                    30,
                    STREAM_CLIENT_CONNECT,
                    $get
                );
                $cert = stream_context_get_params($read);
                $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
                
                if( is_array( $certinfo ) && isset( $certinfo['validTo_time_t'] ) ){
                    return $this->test( $certinfo['validTo_time_t'] );
                }else{
                    return $this->failed( "Error fetching certInfo datas");    
                }
        
            }catch( Exception $e ){
                return $this->failed( $e->getMessage() );
            }


        } else {
            return $this->failed("Failed to initialized");
        }
    }


    private function test( $expiryTsp): array{

        $now = time();
        if( $now < ($expiryTsp-(self::DEFAULT_DELAY_WARNING*86400 /* seconds to days*/) ) ){
            return $this->ok($this->formatResponse( $expiryTsp));
        }elseif( $now >= ($expiryTsp-(self::DEFAULT_DELAY_ALERT*86400 /* seconds to days*/) ) ){    //now > alert
            return $this->alert($this->formatResponse( $expiryTsp));
        }else{  // warn < now < alert
            return $this->warn($this->formatResponse( $expiryTsp));
        }
        

    }



    private function formatResponse( $expiryTsp )
    {
        
        return [
            'expiry' => $expiryTsp,
            'domain' => $this->domain,
            'message' => 'Expire le '.Carbon::createFromTimestamp( $expiryTsp )->toDateTimeString()
        ];
    }
}
