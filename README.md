# DailyTracks


DailyTracks is a simple webpage which suggests a list of youtube videos (music videos).
Everyday, you select the ones you liked the most on the web page.

On the server, everyday, the routine must be executed : it recursively selects youtube suggested videos of the selected one the day before using Youtube API.
The videos are currently ranked using the ratio of comments on views.


## About 

The project uses jquery, and audio assets which here.
Moreover, the youtube API uses a token, which must be written in API_KEY.txt in the root folder.

Everyday, sugested and selected videos are saved in the json format in suggestion.json and selection.json
