<!DOCTYPE html>
<html lang="en">
<head>
    <!-- get bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   
</head>
<body>
    <div class="container">
       <!-- divs for home and away team --> 
         <div class="row">
              <div class="col-6">
                <h1>Home Team</h1>
                <p>Score: <span id="home_score">0</span></p>
              </div>
              <div class="col-6">
                <h1>Away Team</h1>
                <p>Score: <span id="away_score">0</span></p>
              </div>
        </div>
        <!-- divs and text inputs for game settings -->
        <div class="row">
            <div class="col-6">
                <h2>Home Team</h2>
                <p>3 Point Likelihood: <input type="text" id="home_three_pt_likelihood" value="0.3"></p>
                <p>3 Point Percent: <input type="text" id="home_three_pt_percent" value="0.3"></p>
                <p>2 Point Percent: <input type="text" id="home_two_pt_percent" value="0.7"></p>
                <p>Defensive Rebound Percent: <input type="text" id="home_defensive_rebound_percent" value="0.7"></p>
                <p>Fast Break Off Def Rebound Percent: <input type="text" id="home_fast_break_off_def_rebound_percent" value="0.5"></p>
                <p>Fast Break Score Percent: <input type="text" id="home_fast_break_score_percent" value="0.5"></p>
                <p>Off Rebound 2 Point Percent: <input type="text" id="home_off_rebound_two_pt_percent" value="0.5"></p>
                <p>Off Rebound 3 Point Percent: <input type="text" id="home_off_rebound_three_pt_percent" value="0.5"></p>
                <p>Turnover Percent: <input type="text" id="home_turnover_percent" value="0.1"></p>
                <p>Turnover After Rebound Percent: <input type="text" id="home_turnover_after_rebound_percent" value="0.5"></p>
            </div>
            <div class="col-6">
                <h2>Away Team</h2>
                <p>3 Point Likelihood: <input type="text" id="away_three_pt_likelihood" value="0.3"></p>
                <p>3 Point Percent: <input type="text" id="away_three_pt_percent" value="0.3"></p>
                <p>2 Point Percent: <input type="text" id="away_two_pt_percent" value="0.7"></p>
                <p>Defensive Rebound Percent: <input type="text" id="away_defensive_rebound_percent" value="0.7"></p>
                <p>Fast Break Off Def Rebound Percent: <input type="text" id="away_fast_break_off_def_rebound_percent" value="0.5"></p>
                <p>Fast Break Score Percent: <input type="text" id="away_fast_break_score_percent" value="0.5"></p>
                <p>Off Rebound 2 Point Percent: <input type="text" id="away_off_rebound_two_pt_percent" value="0.5"></p>
                <p>Off Rebound 3 Point Percent: <input type="text" id="away_off_rebound_three_pt_percent" value="0.5"></p>
                <p>Turnover Percent: <input type="text" id="away_turnover_percent" value="0.1"></p>
                <p>Turnover After Rebound Percent: <input type="text" id="away_turnover_after_rebound_percent" value="0.5"></p>
            </div>
        
    
         <!-- button to run the game -->
        <div class="row">
            <div class="col-12">
                <button onclick="runGame()">Run Game</button>
            </div>
            <!-- div to output the game results --> 
            <div class="col-12">
                <h2>Game Results</h2>
                <div id="results"></div>
    </div>
</body>
<script>
        var home_score=0;
        var away_score=0;

        // game settings
         // each team has a percent chance of taking a 3 point shot
        // each team has a percent chance of making a 3 point shot
        // each team has a percent chance of making a 2 point shot
        // each team has a percent chance of getting a offensive rebound
        // each team has a percent chance of getting a defensive rebound
        // each team has a percent chance of getting a fast break off a defensive rebound
        // each team has a percent chance of scoring on a fast break
        // each team has a percent chance of scoring a 2 pointer on a offensive rebound
        // each team has a percent chance of scoring a 3 pointer on a offensive rebound
        // each team has a percent chance of turning the ball over
        // each team has a percent chance of turning the ball over on an offensive rebound

        // put the teams and team settings in an JSON object so we can loop through them
        
        var teams = {
            "home": {
                "three_pt_likelihood": 0.3,
                "three_pt_percent": 0.3,
                "two_pt_percent": 0.7,
                "defensive_rebound_percent": 0.7,
                "fast_break_off_def_rebound_percent": 0.5,
                "fast_break_score_percent": 0.5,
                "turnover_percent": 0.1,
                "turnover_after_rebound_percent": 0.5,
                "score": 0
            },
            "away": {
                "three_pt_likelihood": 0.3,
                "three_pt_percent": 0.3,
                "two_pt_percent": 0.7,
                "defensive_rebound_percent": 0.7,
                "fast_break_off_def_rebound_percent": 0.5,
                "fast_break_score_percent": 0.5,
                "turnover_percent": 0.1,
                "turnover_after_rebound_percent": 0.5,
                "score": 0
            }
        }

       
    
        function jumpBall(){
            // return home or away based on a coin toss
            return Math.random() < 0.5 ? "home" : "away";
        }

        function didTurnover( team ){
            // return true or false based on the team's turnover percent
            return Math.random() < teams[team]["turnover_percent"];
        }

        function didShootThree( team ){
            // return true or false based on the team's 3 point percent
            return Math.random() < teams[team]["three_pt_likelihood"];
        }

        function didMakeThree( team ){
            // return true or false based on the team's 3 point percent
            return Math.random() < teams[team]["three_pt_percent"];
        }

        function didMakeTwo( team ){
            // return true or false based on the team's 2 point percent
            return Math.random() < teams[team]["two_pt_percent"];
        }

        function whoGotRebound(){
            // return home or away based on defensive rebound percent and offensive rebound percent
            return Math.random() < teams["home"]["defensive_rebound_percent"] ? "home" : "away";
        }



        function didGetFastBreak( team ){
            // return true or false based on the team's fast break off def rebound percent
            return Math.random() < teams[team]["fast_break_off_def_rebound_percent"];
        }

        function didScoreFastBreak( team ){
            // return true or false based on the team's fast break score percent
            return Math.random() < teams[team]["fast_break_score_percent"];
        }


        function runGame(){

            var results = [];
            
            // reset the scores
            teams["home"]["score"] = 0;
            teams["away"]["score"] = 0;

            var current_possession = jumpBall();
            for(var i=0; i<200; i++){

                // get the current team
                var current_team = teams[current_possession];

                // get the other team
                var other_team = current_possession == "home" ? "away" : "home";

                // did the current team turn the ball over?
                if ( didTurnover( current_possession ) ){

                    results[results.length] = current_possession + " turned the ball over";

                    // did the other team get a fast break?
                    if ( didGetFastBreak( current_possession ) ){
                        results[results.length] = other_team + " got a fast break";
                        // did the other team score on the fast break?
                        if ( didScoreFastBreak( current_possession ) ){
                            results[results.length] = other_team + " scored on the fast break";
                            // if so, add the points to the other team's score, keep posession the same and continue to the next possession
                            other_team["score"] += 2;
                            continue;
                        }
                    }
                    // switch posession and continue to the next possession
                    current_possession = current_possession == "home" ? "away" : "home";
                    continue;
                }

                // current team shoots either 3 or 2 based on percentages
                if ( didShootThree( current_possession ) ){
                    results[results.length] = current_possession + " shot a 3 pointer";
                    // did the current team make the shot?
                    if ( didMakeThree( current_possession ) ){
                        results[results.length] = current_possession + " made the 3 pointer";
                        // if so, add the points to the current team's score, switch posession and continue to the next possession
                        current_team["score"] += 3;
                        current_possession = current_possession == "home" ? "away" : "home";
                        continue;
                    }
                    else{
                        // who got the rebound?
                        var rebound_team = whoGotRebound();
                        results[results.length] = rebound_team + " got the rebound";
                        // if rebound team is current team, did they turn the ball over, score a 2 pointer or score a 3 pointer?
                        if ( rebound_team == current_possession ){
    
                            continue;
                           
                        }
                        // if rebound team is other team, did they get a fast break?
                        else{
                            // did the other team get a fast break?
                            if ( didGetFastBreak( other_team ) ){
                                results[results.length] = other_team + " got a fast break";
                                // did the other team score on the fast break?
                                if ( didScoreFastBreak( current_possession ) ){
                                    results[results.length] = other_team + " scored on the fast break";
                                    // if so, add the points to the other team's score, keep posession the same and continue to the next possession
                                    other_team["score"] += 2;
                                    continue;
                                }
                            }
                            // switch posession and continue to the next possession
                            current_possession = current_possession == "home" ? "away" : "home";
                            continue;
                        }
                        
                    }
                } else {
                    results[results.length] = current_possession + " shot a 2 pointer";
                    // did the current team make the shot?
                    if ( didMakeTwo( current_possession ) ){
                        results[results.length] = current_possession + " made the 2 pointer";
                        // if so, add the points to the current team's score, switch posession and continue to the next possession
                        current_team["score"] += 2;
                        current_possession = current_possession == "home" ? "away" : "home";
                        continue;
                    }
                }

            }
            



            // display the results
            document.getElementById("home_score").innerHTML = teams["home"]["score"];
            document.getElementById("away_score").innerHTML = teams["away"]["score"];

            // display the results
            document.getElementById("results").innerHTML = results.join("<br />");

        }
        

        // event listeners for the settings inputs,  update on change event for the appropriate team.

        document.getElementById("home_three_pt_likelihood").addEventListener("change", function(){
            console.log( "home three pt likelihood changed to " + this.value );
            teams["home"]["three_pt_likelihood"] = this.value;
        });

        document.getElementById("home_three_pt_percent").addEventListener("change", function(){
            console.log( "home three pt percent changed to " + this.value );
            teams["home"]["three_pt_percent"] = this.value;
        });
        
        document.getElementById("home_two_pt_percent").addEventListener("change", function(){
            console.log( "home two pt percent changed to " + this.value );
            teams["home"]["two_pt_percent"] = this.value;
        });
        document.getElementById("home_three_pt_percent").addEventListener("change", function(){
            console.log( "home three pt percent changed to " + this.value)
            teams["home"]["three_pt_percent"] = this.value;
        });
        document.getElementById("home_fast_break_off_def_rebound_percent").addEventListener("change", function(){
            console.log( "home fast break off def rebound percent changed to " + this.value)
            teams["home"]["fast_break_off_def_rebound_percent"] = this.value;
        });
        document.getElementById("home_fast_break_score_percent").addEventListener("change", function(){
            console.log( "home fast break score percent changed to " + this.value)
            teams["home"]["fast_break_score_percent"] = this.value;
        });
        document.getElementById("home_off_rebound_two_pt_percent").addEventListener("change", function(){
            console.log( "home off rebound two pt percent changed to " + this.value);
            teams["home"]["off_rebound_two_pt_percent"] = this.value;
        });
        document.getElementById("home_off_rebound_three_pt_percent").addEventListener("change", function(){
            console.log( "home off rebound three pt percent changed to " + this.value);
            teams["home"]["off_rebound_three_pt_percent"] = this.value;
        });
        document.getElementById("home_turnover_percent").addEventListener("change", function(){
            console.log( "home turnover percent changed to " + this.value);
            teams["home"]["turnover_percent"] = this.value;
        });
        document.getElementById("home_turnover_after_rebound_percent").addEventListener("change", function(){
            console.log( "home turnover after rebound percent changed to " + this.value);
            teams["home"]["turnover_after_rebound_percent"] = this.value;
        });


        document.getElementById("away_three_pt_likelihood").addEventListener("change", function(){
            console.log( "away three pt likelihood changed to " + this.value );
            teams["away"]["three_pt_likelihood"] = this.value;
        });

        document.getElementById("away_three_pt_percent").addEventListener("change", function(){
            console.log( "away three pt percent changed to " + this.value );
            teams["away"]["three_pt_percent"] = this.value;
        });
        document.getElementById("away_two_pt_percent").addEventListener("change", function(){
            console.log( "away two pt percent changed to " + this.value );
            teams["away"]["two_pt_percent"] = this.value;
        });
        document.getElementById("away_three_pt_percent").addEventListener("change", function(){
            console.log( "away three pt percent changed to " + this.value)
            teams["away"]["three_pt_percent"] = this.value;
        });
        document.getElementById("away_fast_break_off_def_rebound_percent").addEventListener("change", function(){
            console.log( "away fast break off def rebound percent changed to " + this.value)
            teams["away"]["fast_break_off_def_rebound_percent"] = this.value;
        });
        document.getElementById("away_fast_break_score_percent").addEventListener("change", function(){
            console.log( "away fast break score percent changed to " + this.value)
            teams["away"]["fast_break_score_percent"] = this.value;
        });
        document.getElementById("away_off_rebound_two_pt_percent").addEventListener("change", function(){
            console.log( "away off rebound two pt percent changed to " + this.value);
            teams["away"]["off_rebound_two_pt_percent"] = this.value;
        });
        document.getElementById("away_off_rebound_three_pt_percent").addEventListener("change", function(){
            console.log( "away off rebound three pt percent changed to " + this.value);
            teams["away"]["off_rebound_three_pt_percent"] = this.value;
        });
        document.getElementById("away_turnover_percent").addEventListener("change", function(){
            console.log( "away turnover percent changed to " + this.value);
            teams["away"]["turnover_percent"] = this.value;
        });
        document.getElementById("away_turnover_after_rebound_percent").addEventListener("change", function(){
            console.log( "away turnover after rebound percent changed to " + this.value);
            teams["away"]["turnover_after_rebound_percent"] = this.value;
        });

    </script>
</html>

