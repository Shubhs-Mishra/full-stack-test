# Answers to technical questions

## How long did you spend on the coding test?
I spent about 5 hours building the app, including the PHP CRUD admin panel, frontend slider layout, and database schema.

If I had more time, I would add:
- authentication and authorization for the admin panel
- better CRUD UX with a success message that disappears automatically
- edge case handling for many slides, especially to prevent the slider dots from distorting
- a cleaner admin UI
- real image upload support instead of fixed image paths
- improved keyboard navigation and screen reader support
- animation and swipe support for the slider on mobile devices
- I have used constants and constant arrays in a few places for simplicity. In a larger production application, this logic would likely be abstracted into more modular and configurable structures. However, given the scope of the assignment, I chose a simpler implementation approach to avoid adding unnecessary complexity and development overhead without much practical benefit.

## How would you track down a performance issue in production?
I would first identify which page or user flow is slow. Then I would check logs, review slow database queries, and inspect the page for large files or extra requests. I would confirm that the SQL queries use the right indexes and look for frontend issues like large images or unused scripts.

If needed, I would add logging or profiling around database calls to see whether the issue is backend, frontend, or network related. After that, I would use caching, pagination, or asset optimization depending on the root cause.

## Please describe yourself using JSON.
{
    "name": "Shubham Mishra",
    "designation": "Full Stack Software Engineer",
    "experience": "3+ years",
    "specialization": "Backend-heavy full stack development",
    "skills": [
        "PHP",
        "Laravel",
        "MySQL",
        "PostgreSQL",
        "Redis",
        "Vue.js",
        "React.js",
        "JavaScript",
        "HTML5",
        "CSS3",
        "Bootstrap",
        "jQuery",
        "REST APIs",
        "System Design"
    ],
    "strengths": [
        "scalable backend architecture",
        "clean and maintainable code",
        "performance optimization",
        "database design",
        "frontend integration",
        "team collaboration"
    ],
    "experienceHighlights": {
        "teamLeadership": "Mentored and guided a team of developers",
        "productOwnership": "Worked extensively on long-term product development",
        "fullStackDevelopment": true
    },
    "approach": "Build practical, scalable, and maintainable applications with strong focus on backend architecture and user experience.",
    "values": {
        "clarity": true,
        "simplicity": true,
        "performance": true,
        "ownership": true,
        "teamwork": true
    }
}