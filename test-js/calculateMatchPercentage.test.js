//Test af calculateMatchPercentage funktionen
const calculateMatchPercentage = require('../ScoringSystem');

test('test at den returnerer den rigtige procent', async () => {
    const person1 = {
        goals: JSON.stringify(["goal1", "goal2", "goal3"]),
        interests: JSON.stringify(["interest1", "interest2", "interest3"]),
        age: 20
    };
    const person2 = {
        goals: JSON.stringify(["goal1", "goal2", "goal3"]),
        interests: JSON.stringify(["interest1", "interest2", "interest3"]),
        age: 20
    };

    const result = await calculateMatchPercentage(person1, person2, 0);
    expect(result).toEqual(1);
})

