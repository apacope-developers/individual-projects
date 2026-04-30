# OpenAI API Setup Instructions

## Add Your OpenAI API Key

1. Open your `.env` file in the root of your project
2. Add this line to the file (replace with your actual key):

```
OPENAI_API_KEY=your-api-key-here
```

3. Save the file
4. Clear the configuration cache by running:
   ```
   php artisan config:clear
   ```

## Test the AI Service

After adding the key, you can test the AI functionality by:

1. Going to your dashboard
2. Using the emergency search with terms like "chest pain" or "difficulty breathing"
3. The system will now use OpenAI for real AI-powered recommendations

## What to Expect

- Real-time AI-generated first aid recommendations
- More detailed and accurate medical guidance
- Personalized emergency suggestions based on your input
- Voice search will work with OpenAI processing

The system will automatically detect the OpenAI API key and use it as the primary AI provider.
