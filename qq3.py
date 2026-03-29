import pandas as pd
from scipy.stats import shapiro
import matplotlib.pyplot as plt
import scipy.stats as stats

# Load data
df = pd.read_csv("data.csv")

# Compute NASA TLX Total
df["NASA_TLX_Total"] = (
    df["Mental"] +
    df["Physical"] +
    df["Temporal"] +
    df["Performance"] +
    df["Effort"] +
    df["Frustration"]
)

# --- Shapiro–Wilk Test ---
print("\n=== SHAPIRO–WILK: NASA TLX TOTAL ===")
stat, p = shapiro(df["NASA_TLX_Total"])
print("Statistic:", stat)
print("p-value:", p)

# --- Q–Q Plot ---
plt.figure(figsize=(6,6))
stats.probplot(df["NASA_TLX_Total"], dist="norm", plot=plt)
plt.title("Q–Q Plot – NASA TLX Total")
plt.xlabel("Theoretical Quantiles")
plt.ylabel("Sample Quantiles")
plt.show()