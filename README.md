# DailyTracks


DailyTracks is a simple webpage which suggests a list of youtube videos (music videos) to the user.
Everyday, you select the ones you liked the most on the web page.

On the server, everyday, the routine must be executed : it recursively selects youtube related videos of the selected ones the day before, using Youtube API.
The videos are currently ranked using the ratio comments/views to display them to the user.


## About 

The project uses jquery, and audio assets which are not here.
Moreover, the youtube API uses a token, which must be written in API_KEY.txt in the root folder.

Everyday, sugested and selected videos are saved in the json format in suggestion.json and selection.json
