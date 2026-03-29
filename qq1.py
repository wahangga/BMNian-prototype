import pandas as pd
from scipy.stats import shapiro
import matplotlib.pyplot as plt
import scipy.stats as stats

df = pd.read_csv("data.csv")
df = df.rename(columns={
    "Task perfomance error (from total 7 objectives)": "TaskError"
})

# Shapiro–Wilk test
stat, p = shapiro(df["TaskError"])
print("Task Error – Shapiro Test")
print("Statistic:", stat)
print("p-value:", p)

# Q–Q plot
plt.figure(figsize=(6,6))
stats.probplot(df["TaskError"], dist="norm", plot=plt)
plt.title("Q–Q Plot – Task Error")
plt.show()