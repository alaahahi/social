<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Massage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class SocialMediaController extends Controller
{
    public function __construct(){
        $this->url = env('FRONTEND_URL');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('check-username');
    }

  public function checkUsername(Request $request)
    {
        $request->validate([
            'platform' => 'required',
            'username' => 'required',
        ]);

        $platform = $request->input('platform');
        $username = $request->input('username');
        $result = $this->checkUsernameExists($platform, $username);

        return view('check-username', compact('result', 'platform', 'username'));
    }

    private function checkTwitterUsername($username)
    {
        $client = new Client();
        $bearerToken = env('TWITTER_BEARER_TOKEN');
        $url = "https://api.twitter.com/2/users/by/username/$username";
    
        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => "Bearer $bearerToken",
                ],
            ]);
    
            // If response is successful, user exists
            if ($response->getStatusCode() == 200) {
                $userData = json_decode($response->getBody()->getContents(), true);
                return [
                    'status' => 'exists',
                    'platform' => 'twitter',
                    'username' => $userData['data']['username'] ?? '',
                    'name' => $userData['data']['name'] ?? '',
                ];
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 404) {
                return [
                    'status' => 'not_found',
                    'message' => "The username @$username does not exist or has been removed on Twitter.",
                ];
            }
            return [
                'status' => 'error',
                'uhwwwqwhhhhhhhhhhhmmm' => "An error occurred: " . $e->getMessage(),
            ];
        }
    }
    
    private function checkUsernameExists($platform, $username)
    {
        $client = new Client();
        $url = '';
    
        switch ($platform) {
            case 'facebook':
                $url = "https://www.facebook.com/$username";
                break;
            case 'instagram':
                $url = "https://www.instagram.com/$username";
                break;
            case 'twitter':
                // Use Twitter API method for Twitter
                return $this->checkTwitterUsername($username);
            default:
                return "Unsupported platform.";
        }
    
        try {
            $response = $client->request('GET', $url);
    
            if ($response->getStatusCode() === 200) {
                $html = $response->getBody()->getContents();
                $crawler = new Crawler($html);
    
                $metadata = [
                    'title' => $crawler->filter('meta[property="og:title"]')->attr('content') ?? 'N/A',
                    'description' => $crawler->filter('meta[property="og:description"]')->attr('content') ?? 'N/A',
                    'image' => $crawler->filter('meta[property="og:image"]')->attr('content') ?? 'N/A',
                ];
    
                return [
                    'status' => "Username exists on $platform.",
                    'metadata' => $metadata,
                ];
            } else {
                return [
                    'status' => "Username does not exist or is unavailable on $platform.",
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => "Username does not exist or is unavailable on $platform. 404 ",
            ];
        }

    }
   
}
