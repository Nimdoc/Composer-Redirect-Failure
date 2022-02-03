# Composer Redirect Failure

This project was made to demonstrate how the queueing of download jobs can break the installation process in composer when a redirect to a download that can expire is encountered.

## Steps

1. Make sure PHP and Composer are installed on your local machine

2. Clone the repository to your local machine 

3. Open a terminal session, navigate to the Server directory of the repository and run:

```php -S localhost:8123 index.php```

4. Open another terminal session and navigate to the Client directory of the repository and run the following:

```composer clearcache```

```composer install -n -vvv```

As you will see, composer will fail with a message like:

```[Composer\Downloader\TransportException] The 'http://localhost:8123/package2/download?timestamp=1643926024' URL could not be accessed: HTTP/1.0 403 Unauthorized```

The reason is that when composer attempts to download multiple packages (like any normal php project), and when a redirect is encountered, the redirect is sent to the back of the “job queue.” This can potentially break the installation process when the redirect is to a link that expires. 
