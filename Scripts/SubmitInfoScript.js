window.globalVarCurrentEntryNumber = 1;

$(document).ready(function(){
    $("#addMoreInfoButton").click(function() //@@@@@ doesn't work 
    {
        var $currContainer = "#singleIssueSubmissionContainer" + window.globalVarCurrentEntryNumber;
        var $currTopic = "[name=topic" + window.globalVarCurrentEntryNumber + "]"; //last topic selector name selector
            var $currTopicLabel = "label[for=topic" + window.globalVarCurrentEntryNumber + "]";
        var $currContent = "[name=contentTextarea" + window.globalVarCurrentEntryNumber + "]"; //last content textarea name selector
            var $currContentLabel = "label[for=contentTextarea" + window.globalVarCurrentEntryNumber + "]";
        var $currSource = "[name=sourceTextarea" + window.globalVarCurrentEntryNumber + "]"; //last source textarea name selector
            var $currSourceLabel = "label[for=sourceTextarea" + window.globalVarCurrentEntryNumber + "]";

        window.globalVarCurrentEntryNumber++; //increment to create next submission container
        var $nextTopic = "topic" + window.globalVarCurrentEntryNumber; //next topic selector name
        var $nextContent = "contentTextarea" + window.globalVarCurrentEntryNumber; //next content textarea name
        var $nextSource = "sourceTextarea" + window.globalVarCurrentEntryNumber; //next source textarea name

        var $copyHtml = $($currContainer); //the last whole issue submission

        $copyHtml.$($currTopic).prop('name', $nextTopic); //rename the next topic selector
            $copyHtml.$($currTopic + "[value=empty]").prop('selected', 'selected'); //reinitialize it to default value
            $copyHtml.$($currTopicLabel).prop('for', $nextTopic); //rename the label for the next topic selector
        $copyHtml.$($currContent).prop('name', $nextContent);
            $copyHtml.$($currContent).text = '';
            $copyHtml.$($currContentLabel).prop('for', $nextContent);
        $copyHtml.$($currSource).prop('name', $nextSource);
            $copyHtml.$($currSource).text = '';
            $copyHtml.$($currSourceLabel).prop('for', $nextSource);

        $currContainer.$(".addMoreInfoButtonContainer").remove(); //gets rid of the button in the last submission container

        $($currContainer).append($copyHtml);
    });

});























