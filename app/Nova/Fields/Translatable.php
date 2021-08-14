<?php


namespace App\Nova\Fields;

use Illuminate\Contracts\Validation\Rule;
use Laravel\Nova\Fields\Field;
use Spatie\NovaTranslatable\Translatable as SpatieTranslatable;


class Translatable extends SpatieTranslatable
{

    /**
     * Set custom validation rules for the fields.
     *
     * @param callable|array|string $rules
     * @return $this
     */
    public function rulesFor($rules = []): Translatable
    {
        $rules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;
        collect($this->data)->each(function (Field $field) use ($rules) {
            list(, $attr, $locale) = explode('_', $field->attribute);
            $field->rules($rules[$attr][$locale]);
        });

        return $this;
    }

    protected function createTranslatedField(Field $originalField, string $locale): Field
    {
        $originalAttribute = $originalField->attribute;
        $translatedField = parent::createTranslatedField($originalField, $locale);
        $translatedField->attribute = 'translations_'.$originalAttribute.'_'.$locale;

        return $translatedField;
    }
}
