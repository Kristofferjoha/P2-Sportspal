const maxPoints = 50;
const normalizeRating = 9;
const maximumAllowedImpact = 0.2;

// Function to calculate the match percentage between two users
async function calculateMatchPercentage(person1, person2, ignoreMode) {
    let matchPercentage = 0;
    const genderPreference = person1.genderToggle;

    const person1Goals = JSON.parse(person1.goals);
    console.log(person1Goals);
    const person2Goals = JSON.parse(person2.goals);
    console.log(person2Goals);
    const person1Interests = JSON.parse(person1.interests);
    console.log(person1Interests);
    const person2Interests = JSON.parse(person2.interests);
    console.log(person2Interests);

    // Calculate activity score
    matchPercentage += 20;

    console.log(matchPercentage);


// Calculate similarity score based on common goals
const commonGoals = person1Goals.filter(goal => person2Goals.includes(goal));
matchPercentage += (commonGoals.length / person1Goals.length) * 10;

console.log(matchPercentage);

// Calculate similarity score based on common interests
    const commonInterests = person1Interests.filter(interest => person2Interests.includes(interest));
    matchPercentage += (commonInterests.length / person1Interests.length) * 10;

    console.log(matchPercentage);

// Calculate age score logarithmically
const ageDifference = Math.abs(person1.age - person2.age);
let ageScore;
if (ageDifference <= 1) {
    ageScore = 10;
} else {
    ageScore = 10 - (2 * Math.log(ageDifference + 1) + 0.2 * (ageDifference - 1));
}
// Math.max(0, ...): Ensures the score does not go below 0.
// Math.min(..., 10): Ensures the score does not exceed 10.
ageScore = Math.max(0, Math.min(ageScore, 10));

matchPercentage += ageScore;

console.log(matchPercentage);

return matchPercentage / maxPoints;


}

// Function to run the algorithm
async function runAlgorithm() {
    try {
        // Fetch user from the server
        const person1Response = await fetch('algorithm.php');
        const person1 = await person1Response.json();

        const response = await fetch(`fetch_users.php?activities=${person1.activities}&timespan=${person1.timespan}&location=${person1.location}&date=${person1.date}&niveau=${person1.niveau}`);
        const users = await response.json();

        console.log(users);

        // Array to store the best matches
        const bestMatches = [];

        // Match the person with others
        for (let j = 0; j < users.length; j++) {
            const person2 = users[j];

            console.log("Im matching", users[j]);

            // Skip if person2 is the same as person1
            if (person2.user_id === person1.user_id) {
                continue;
            }

            // Calculate match percentage
            const matchPercentage = await calculateMatchPercentage(person1, person2, false);
            console.log(matchPercentage);

            bestMatches.push({
                person: person2,
                user_id: person2.user_id,
                username: person2.username,
                matchPercentage: matchPercentage
            });
        }

        // Sort matches in descending order of matchPercentage
        bestMatches.sort((a, b) => b.matchPercentage - a.matchPercentage);
        
        const topMatches = bestMatches;

        // Call predictRatings function
        await predictRatings(person1, topMatches);

    } catch (error) {
        console.error('Error:', error.message);
    }
}

function logit(p) {
    return Math.log(p / (1 - p));
}
function sigmoid(z) {
    return 1 / (1 + Math.exp(-z));
  }

// Function to predict ratings for the top 5 matches
async function predictRatings(person1, bestMatches) {
    try {
        const matchesWithAdjustedPercentage = [];

        for (let i = 0; i < 5; i++) {
            const person2 = bestMatches[i].person;

            // Fetch ratings from the database for the current user
            const response = await fetch(`fetch_ratings.php?user_id=${person2.user_id}`);
            const ratingsData = await response.json();

            console.log(ratingsData);

            const ratingsLength = ratingsData.length;
            const weightedRatings = [];

            for (let j = 0; j < ratingsLength; j++) {
                const rating = ratingsData[j].score;
                const raterUsername = ratingsData[j].rater_username;

                console.log(rating, raterUsername);

                // Fetch user data for the rater
                const raterResponse = await fetch(`fetch_users.php?username=${raterUsername}`);
                const raterData = await raterResponse.json();

                console.log(raterData);

                if (raterData) {
                    // Calculate match percentage using the rater's information
                    const matchPercentage = await calculateMatchPercentage(person1, raterData, true);
                    const weightedRating = {
                        weightedRating: rating * matchPercentage,
                        matchPercentage: matchPercentage
                    };
                    weightedRatings.push(weightedRating);
                } else {
                    console.log(`No user found with username: ${raterUsername}`);
                }
            }

            // Calculate the average of the weighted ratings
            if (weightedRatings.length > 0) {
                const sumWeightedRatings = weightedRatings.reduce((sum, wr) => sum + wr.weightedRating, 0);
                const sumOfMatchPercentages = weightedRatings.reduce((sum, wr) => sum + wr.matchPercentage, 0);
                const preditedRating = sumWeightedRatings / sumOfMatchPercentages;
                const xValueImpact = ((preditedRating - 1) / 9) * 2 - 1;

                // Calculate the x-value from the original match percentage
                const originalXValue = logit(bestMatches[i].matchPercentage);
                // Adjust the x-value based on the adjustment factor
                const adjustedXValue = originalXValue + xValueImpact;

                // Apply the sigmoid function to the adjusted x-value
                const adjustedMatchPercentage = sigmoid(adjustedXValue);
                bestMatches[i].matchPercentage = adjustedMatchPercentage;

                console.log("ADJUSTED MATCH PERCENTAGE: ", adjustedMatchPercentage);
            }

            await updateDatabase(person2, bestMatches[i].matchPercentage * 100);

            // Push an object into matchesWithAdjustedPercentage array
            matchesWithAdjustedPercentage.push({
                person: person2,
                matchPercentage: bestMatches[i].matchPercentage.toFixed(2) // Adjusted percentage instead of bestMatches[i].matchPercentage
            });
        }

        // Sort matchesWithAdjustedPercentage in descending order of matchPercentage
        matchesWithAdjustedPercentage.sort((a, b) => b.matchPercentage - a.matchPercentage);

        console.log("ADJUSTED PERCENTAGE: ", matchesWithAdjustedPercentage);

        return matchesWithAdjustedPercentage;
    } catch (error) {
        console.error('Error in predicting ratings:', error.message);
        return [];
    }
}


// Function to update the database with the adjusted percentage
async function updateDatabase(user, adjustedPercentage) {
    try {
        console.log(user, adjustedPercentage);
        const response = await fetch('update_database.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                userId: user.user_id,
                adjustedPercentage: adjustedPercentage,
                goals: user.goals,
                interests: user.interests,
                timespan: user.timespan,
                activities: user.activities,
                location:user.location,
                date: user.date,
            }),
        });
        const data = await response.json();
        console.log('Database updated successfully:', data);
    } catch (error) {
        console.error('Error updating database:', error.message);
    }
}

// Run the algorithm
runAlgorithm().catch(console.error);

