Radio-NowPlaying
================
An online song metadata engine for radio stations. Configure your automation/music playout software to send metadata to Radio-NowPlaying, and 
the engine will take care of the rest.

## Features
- Automatic cover art retrieval & audio preview retrieval from Spotify or Deezer (you choose)
- Save the current song metadata in a JSON file
- Add the current song metadata to a JSON playlist file of past songs broadcasted
- Send song updates to streaming servers and other compatible platforms, including : Icecast, Shoutcast and even LastFM !

## Configuration
First, rename the file `config.php.example` to `config.php`. The config file is a PHP script with a single multi-dimensional array with the settings in it.  
The `config.php.example` file contains most of the possible configuration values. Here is a description of some of those :
- "forcePost" (bool) : tells the engine to refuse HTTP song updates which aren't POST requests. GET requests will be blocked (default : true).
- "coverart_fallback" (bool) : tells the engine to query the configured cover art provider when the `coverart` request parameter isn't set (default : false. **You might want to change this**).
- "dataProvider" (string) : the module name of the cover art provider (currently only "Spotify" and "Deezer" are acceptable values).
- "textFilters" (array of strings) : the list of words or regexes to remove from the Title and Artist metadata attributes.
- "allowedHosts" (array of strings) : the list of IP addresses (or DNS hosts that will be resolved to IP addresses) allowed to send metadata updates. If empty or not set, the IP filter is disabled and any IP can send updates to the engine.
- "allowedTokens" (array of strings) : the list of tokens/passwords for access control
- "minDuration" (integer) : the minimum song duration in seconds required for an update to be considered valid.
- "platforms" (associative array of arrays) : Where to save and/or send metadata. See `config.php.example` for the full reference.

## How to send metadata
Make sure your playout system has the ability to send an HTTP request on every song change.  
Configure it to call `onair.php` (complete URL depends on how you installed Radio-NowPlaying) with the following request parameters :
- "type" (string, required) : "Music" or "Show"
- "token" (string, required if configured, optional otherwise) : one of the tokens configured in config.php
- "title" (string, optional) : the current song's artist (required only when "type" equals "Music")
- "artist" (string, required) : the current song's artist
- "duration" (integer, required) : the current song's duration
- "coverart" (string, optional) : the base64-encoded cover art image

## License
Radio-NowPlaying is licensed under the Lesser General Public License (LGPL) version 3.0. You are free to use and integrate this piece of software in your project or website, as long as any modifications are made under the terms of the aforementioned license. Please see the `LICENSE` file for more informations and full license text.

## Closing word
This piece of software is, in no way, perfect. It lacks flexibility and some security features.
However, we're sharing it publicly with the hope that it will be useful to someone or some organization.  
If you're using it in one of your projects (website, internal tool, etc), please send an email to `technique @ rjrradio dot fr` and tell us how you're using it. ;)
