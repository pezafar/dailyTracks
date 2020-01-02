import requests
import json
import numpy as np


API_FILE = "API_KEY.txt"


#Recursive function, gets n suggested videos and applies itself to these new videos
def getSuggested(videoID, numberResults, iterations, idList, titleList, scoreList, key):
    if (iterations == 0):
        return

    keyParam = "&key=" + key

    request =  "https://www.googleapis.com/youtube/v3/search?part=snippet&fields=items(id(videoId),snippet(title))&relatedToVideoId=" + videoID +"&type=video&maxResults=" + str(numberResults+1) + keyParam
    response = requests.get(request)
    j =  json.loads( response.content.decode('utf-8'))

    for x in j['items']:
        title = x['snippet']['title']
        id = x['id']['videoId']

        if (id not in idList):    
            #Add id and title to the lists            
            titleList.append(title)
            idList.append(id)

            #Gather viewCout, commentCount and compute score
            request = "https://www.googleapis.com/youtube/v3/videos?part=statistics&fields=items(statistics(viewCount,commentCount))&id="+id+"&" + keyParam
            response = requests.get(request)

            if (response.status_code != 200):
                print("Error in youtube API request")
                return
            try:
                j =  json.loads( response.content.decode('utf-8'))
            except:
                print("Error loading Json")

            viewCount = int( j['items'][0]['statistics']['viewCount'])
            commentCount = int( j['items'][0]['statistics']['commentCount'])
            score = commentCount/viewCount
            scoreList.append(score)

            #recursion
            getSuggested(idList[-1], numberResults,iterations-1, idList, titleList, scoreList, key)

def startRoutine():
    #Youtube API token located in file
    try:
        with open(API_FILE, "r") as f:
            API_KEY = f.readline().strip("\n")
    except IOError:
        print ("Error loading API token in :", API_FILE)
        return

    titles = []
    ids = []
    scores = []

    #initial videos
    idInit = "yAv5pLO37mE"
    #start
    getSuggested(idInit,2,5,ids, titles, scores, API_KEY)



    #Format and save
    indices = np.argsort(np.array(scores))
    suggestions = []

    for i in range (0, len(ids)):
        j = indices[ len(ids) -1 - i ]
        
        #build suggestion json
        video ={}
        video['id'] = ids[j]
        video['title'] = titles[j]
        video['score'] = str(scores[j]*(10**5))

        suggestions.append(video)

    #saving suggetions
    with open("suggestion.json", "w+", encoding='utf-8') as f:
        json.dump(suggestions,f, indent=4)


startRoutine()