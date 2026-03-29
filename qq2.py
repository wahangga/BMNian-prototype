import pandas as pd
from scipy.stats import shapiro
import matplotlib.pyplot as plt
import scipy.stats as stats

# Load data
df = pd.read_csv("data.csv")

# Convert Task labels (optional, not required for normality)
df["Task"] = df["Task"].replace({1: "Low", 2: "High", 3: "None"})

# --- Shapiro–Wilk Test ---
print("\n=== SHAPIRO–WILK: TASK DURATION ===")
stat, p = shapiro(df["Duration"])
print("Statistic:", stat)
print("p-value:", p)

# --- Q–Q Plot ---
plt.figure(figsize=(6,6))
stats.probplot(df["Duration"], dist="norm", plot=plt)
plt.title("Q–Q Plot – Task Duration")
plt.xlabel("Theoretical Quantiles")
plt.ylabel("Sample Quantiles")
plt.show()