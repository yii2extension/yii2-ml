<?php

namespace yii2extension\ml\domain\helpers;

use NlpTools\Classifiers\ClassifierInterface;
use NlpTools\FeatureFactories\FeatureFactoryInterface;
use NlpTools\Models\MultinomialNBModelInterface;
use NlpTools\Tokenizers\TokenizerInterface;
use NlpTools\Tokenizers\WhitespaceTokenizer;
use NlpTools\Documents\TrainingSet;
use NlpTools\Documents\TokensDocument;
use NlpTools\FeatureFactories\DataAsFeatures;
use NlpTools\Classifiers\MultinomialNBClassifier;
use yii2extension\ml\domain\models\FeatureBasedNB;

class ClassifyHelper {

    /**
     * @var TokenizerInterface
     */
    public $tokenizer;

	/**
	 * @var MultinomialNBModelInterface
	 */
	protected $model;
	
	/**
	 * @var ClassifierInterface
	 */
	protected $classifier;

	public function __construct(TokenizerInterface $tokenizer)
    {
        $this->model = new FeatureBasedNB;
        $this->tokenizer = $tokenizer;
    }

    public function classify($value, array $classes) {
        $prediction = $this->classifier->classify($classes, new TokensDocument($this->tokenizer->tokenize($value)));
        return $prediction;
    }

    public function setModel($model) {
        $this->model->setData($model);
        $features = new DataAsFeatures();
        $this->classifier = new MultinomialNBClassifier($features, $this->model);
    }

    public function getModel()
    {
        return (array) $this->model->getData();
    }

    public function train($training) {
        $trainingSet = new TrainingSet();
        foreach ($training as $trainingDocument) {
            if(count($trainingDocument) > 1) {
                $trainingSet->addDocument($trainingDocument[0], new TokensDocument($this->tokenizer->tokenize($trainingDocument[1])));
            }
        }
        $features = new DataAsFeatures();
        $this->model->train($features, $trainingSet);
        $this->classifier = new MultinomialNBClassifier($features, $this->model);
    }

}
